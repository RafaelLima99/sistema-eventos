<?php

namespace App\Services;

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Enums\StatusIngresso;
use App\Enums\StatusPresenca;
use App\Enums\TipoGatilho;
use App\Jobs\EnviarEmailDaAutomacao;
use App\Models\Automacao;
use App\Models\Inscrito;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Envia as mensagens das automações para os inscritos.
 *
 * Cada automação conta o tempo a partir da inscrição ou do pagamento (TipoGatilho):
 * - Imediatas (0h 0min): enviadas pelos webhooks, na hora da inscrição (Greatpages) ou do pagamento (Hotmart).
 * - Agendadas (ex.: 15 min após a inscrição): enviadas pelo agendador (routes/console.php), que roda a cada minuto.
 *
 * Quem já recebeu cada automação fica registrado na tabela automacao_envios, para não receber de novo.
 * E-mail já é enviado. WhatsApp ainda falta implementar (ver TODO em colocarNaFila()).
 */
class AutomacaoEnvioService
{
    /**
     * Se o horário de envio passou há mais que isso (ex.: o sistema ficou fora do ar), a mensagem não é mais enviada.
     * Evita mandar "Seu pagamento está pendente" dias depois.
     */
    private const HORAS_DE_TOLERANCIA = 24;

    /**
     * Chamado pelos webhooks logo depois da inscrição (AposInscricao) ou do pagamento (AposPagamento).
     * Coloca na fila uma mensagem para cada automação imediata do evento, com esse gatilho, que vale para este inscrito.
     * Se não houver nenhuma, não faz nada.
     */
    public function enviarMensagensImediatasSeHouver(Inscrito $inscrito, TipoGatilho $gatilho): void
    {
        // Automações dos dois canais (e-mail e WhatsApp).
        $automacoes = Automacao::with(['emailMensagem', 'whatsappMensagem'])
            ->where('evento_id', $inscrito->evento_id)
            ->where('status', StatusAutomacao::Ativo)
            ->where('tipo_gatilho', $gatilho)
            // 0 minutos = "Logo após".
            ->where('minutos_disparo', 0)
            ->get();

        foreach ($automacoes as $automacao) {
            // Procura no banco este inscrito com o filtro da automação: se encontrar, ele faz parte do público.
            $queryBuscaInscrito = Inscrito::query()->where('id', $inscrito->id);
            $fazParteDoFiltro = $this->aplicarFiltro($queryBuscaInscrito, $automacao->filtro)->exists();

            // A Hotmart pode reenviar o mesmo webhook: quem já recebeu não recebe de novo.
            $jaRecebeu = $this->inscritoJaRecebeu($automacao, $inscrito);

            if ($fazParteDoFiltro && ! $jaRecebeu) {
                $this->colocarNaFila($automacao, $inscrito);
            }
        }
    }

    /**
     * Chamado pelo agendador a cada minuto (routes/console.php).
     * Para cada automação com tempo (ex.: 15 min após a inscrição ou 1 dia após o pagamento), envia para os inscritos
     * cujo tempo já passou e que ainda não receberam. Se não houver nenhum, não faz nada.
     */
    public function enviarMensagensAgendadasSeHouver(): void
    {
        // Automações dos dois canais (e-mail e WhatsApp).
        $automacoes = Automacao::with(['emailMensagem', 'whatsappMensagem'])
            ->where('status', StatusAutomacao::Ativo)
            // As de 0 minuto são enviadas pelos webhooks (enviarMensagensImediatasSeHouver).
            ->where('minutos_disparo', '>', 0)
            ->get();

        foreach ($automacoes as $automacao) {
            foreach ($this->buscarInscritosQueDevemReceber($automacao) as $inscrito) {
                $this->colocarNaFila($automacao, $inscrito);
            }
        }
    }

    /**
     * Inscritos do evento que já chegaram no horário da automação e ainda não a receberam.
     *
     * @return Collection<int, Inscrito>
     */
    private function buscarInscritosQueDevemReceber(Automacao $automacao): Collection
    {
        // Coluna de onde o tempo é contado: data da inscrição ou data do pagamento.
        // Quem ainda não pagou tem pago_em vazio e não entra nas automações "após o pagamento".
        $coluna = $automacao->tipo_gatilho === TipoGatilho::AposPagamento ? 'pago_em' : 'created_at';

        // Até: horário atual menos o tempo da automação. Quem se inscreveu (ou pagou) até o horário calculado já completou o tempo.
        // Ex.: agora 10:30, automação de 15 min => até 10:15. Quem entrou às 10:10 recebe; às 10:20, ainda não, pois não se passaram 15 min da inscrição (ou do pagamento).
        $ate = now()->subMinutes($automacao->minutos_disparo);

        // A partir de: o mais recente entre 24h antes do "até" e a criação da automação.
        // - Não envia com mais de 24h de atraso (ver HORAS_DE_TOLERANCIA).
        // - Não envia para quem entrou antes de a automação existir: numa automação nova, a tabela
        //   automacao_envios está vazia e não barraria os inscritos antigos.
        //
        // Por que uma janela e não só o horário exato (10:15): se o agendador parar alguns minutos (deploy, reinício),
        // quem completou o tempo nesse intervalo recebe na próxima rodada. A janela não repete envios porque a
        // consulta abaixo só traz quem ainda não recebeu (tabela automacao_envios).
        $aPartirDe = max($ate->copy()->subHours(self::HORAS_DE_TOLERANCIA), $automacao->created_at);

        // Inscritos que já receberam esta automação (tabela automacao_envios).
        // Não é executada aqui: vira uma subconsulta dentro do whereNotIn abaixo.
        $queryInscritosQueJaReceberam = DB::table('automacao_envios')
            ->where('automacao_id', $automacao->id)
            ->select('inscrito_id');

        $query = Inscrito::query()
            ->where('evento_id', $automacao->evento_id)
            ->whereBetween($coluna, [$aPartirDe, $ate])
            // Só quem ainda não recebeu.
            ->whereNotIn('id', $queryInscritosQueJaReceberam);

        return $this->aplicarFiltro($query, $automacao->filtro)->get();
    }

    /**
     * Procura na tabela automacao_envios a linha desta automação com este inscrito.
     * Se encontrar, é porque a mensagem já foi enviada.
     */
    private function inscritoJaRecebeu(Automacao $automacao, Inscrito $inscrito): bool
    {
        return DB::table('automacao_envios')
            ->where('automacao_id', $automacao->id)
            ->where('inscrito_id', $inscrito->id)
            ->exists();
    }

    /**
     * Grava na tabela automacao_envios que esta automação foi enviada para este inscrito.
     */
    private function registrarEnvio(Automacao $automacao, Inscrito $inscrito): void
    {
        DB::table('automacao_envios')->insert([
            'automacao_id' => $automacao->id,
            'inscrito_id' => $inscrito->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Restringe a consulta de inscritos ao filtro (público) escolhido na automação.
     * O status é lido na hora do envio: quem pagou antes dos 15 minutos não recebe o lembrete de "não pagantes".
     *
     * @param  Builder<Inscrito>  $query
     * @return Builder<Inscrito>
     */
    private function aplicarFiltro(Builder $query, FiltroPublico $filtro): Builder
    {
        if ($filtro === FiltroPublico::Pagantes) {
            return $query->where('status_ingresso', StatusIngresso::Pago);
        }

        if ($filtro === FiltroPublico::NaoPagantes) {
            return $query->where('status_ingresso', StatusIngresso::Pendente);
        }

        // Presença só é marcada no dia do evento: logo após a inscrição ninguém é presente nem ausente ainda.
        if ($filtro === FiltroPublico::Presentes) {
            return $query->where('status_presenca', StatusPresenca::Presente);
        }

        if ($filtro === FiltroPublico::Ausentes) {
            return $query->where('status_presenca', StatusPresenca::Ausente);
        }

        // "Toda a base" (sem filtro): todos os inscritos.
        // Atenção: um filtro novo criado no enum também cai aqui (envia para todos) até ganhar o seu if.
        return $query;
    }

    /**
     * Registra que o inscrito recebeu a automação e coloca a mensagem na fila.
     * O envio de verdade acontece no job, fora do webhook e do agendador.
     */
    private function colocarNaFila(Automacao $automacao, Inscrito $inscrito): void
    {
        if ($automacao->canal === CanalMensagem::Email) {
            // Registra antes de enviar: na próxima rodada do agendador, este inscrito já não aparece.
            $this->registrarEnvio($automacao, $inscrito);

            EnviarEmailDaAutomacao::dispatch($automacao, $inscrito);
        }

        if ($automacao->canal === CanalMensagem::Whatsapp) {
            // TODO WhatsApp: falta implementar o envio. Passos:
            // 1. Criar o WhatsappService com um método enviarTemplate(), que chama a API da Meta:
            //    POST https://graph.facebook.com/{versao}/{WHATSAPP_PHONE_NUMBER_ID}/messages
            //    (nova variável no .env: WHATSAPP_PHONE_NUMBER_ID, o ID do número que envia).
            //    Enviar: telefone no formato 55 + DDD + número, nome e idioma do template
            //    (buscarAprovadoPorNome() do WhatsappTemplateService traz o idioma) e os valores das variáveis.
            // 2. Montar os valores das variáveis: $automacao->whatsappMensagem->variaveis_template diz
            //    qual campo do sistema vai em cada {{n}} (ex.: 1 => 'nome_inscrito'); trocar pelo dado do inscrito.
            // 3. Criar o job EnviarWhatsappDaAutomacao (igual ao EnviarEmailDaAutomacao) e descomentar:
            // $this->registrarEnvio($automacao, $inscrito);
            // EnviarWhatsappDaAutomacao::dispatch($automacao, $inscrito);
            // O resto já está pronto: os envios imediatos e o agendador já trazem as automações de WhatsApp até aqui.
        }
    }
}
