<?php

namespace App\Services;

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Enums\StatusIngresso;
use App\Jobs\EnviarEmailDaAutomacao;
use App\Models\Automacao;
use App\Models\Inscrito;

/**
 * Envia as mensagens das automações para os inscritos.
 *
 * Por enquanto só o envio imediato ("Logo após a inscrição", 0h 0min).
 * E-mail já é enviado. WhatsApp já é encontrado aqui, mas o envio ainda falta implementar (ver TODO abaixo).
 * Automações com tempo (ex.: 2h após a inscrição) ainda não são enviadas.
 */
class AutomacaoEnvioService
{
    /**
     * Chamado logo depois que uma inscrição nova é criada.
     * Coloca na fila uma mensagem para cada automação imediata do evento que vale para este inscrito.
     * Se não houver nenhuma, não faz nada.
     */
    public function enviarMensagensImediatasSeHouver(Inscrito $inscrito): void
    {
        // Automações dos dois canais (e-mail e WhatsApp).
        $automacoes = Automacao::with(['emailMensagem', 'whatsappMensagem'])
            ->where('evento_id', $inscrito->evento_id)
            ->where('status', StatusAutomacao::Ativo)
            // 0 minutos = "Logo após a inscrição".
            ->where('minutos_disparo', 0)
            ->get();

        foreach ($automacoes as $automacao) {
            if (! $this->inscritoFazParteDoFiltro($automacao->filtro, $inscrito)) {
                continue; // Este inscrito não é do público da automação: passa para a próxima.
            }

            // Vai para a fila: o envio de verdade acontece no job, fora do webhook.
            if ($automacao->canal === CanalMensagem::Email) {
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
                // EnviarWhatsappDaAutomacao::dispatch($automacao, $inscrito);
            }
        }
    }

    /**
     * Confere se o inscrito faz parte do filtro (público) escolhido na automação.
     */
    private function inscritoFazParteDoFiltro(FiltroPublico $filtro, Inscrito $inscrito): bool
    {
        // "Toda a base": todo inscrito recebe.
        if ($filtro === FiltroPublico::SemFiltro) {
            return true;
        }

        if ($filtro === FiltroPublico::Pagantes) {
            return $inscrito->status_ingresso === StatusIngresso::Pago;
        }

        if ($filtro === FiltroPublico::NaoPagantes) {
            return $inscrito->status_ingresso === StatusIngresso::Pendente;
        }

        // Presentes e Ausentes: presença só é marcada no dia do evento,
        // então logo após a inscrição ninguém é presente nem ausente ainda.
        // Atenção: um filtro novo criado no enum também cai aqui (não envia) até ganhar o seu if.
        return false;
    }
}
