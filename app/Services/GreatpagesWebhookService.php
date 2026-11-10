<?php

namespace App\Services;

use App\Enums\StatusIngresso;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Support\Str;

/**
 * Transforma o formulário preenchido na landing page da Greatpages em uma inscrição no evento.
 */
class GreatpagesWebhookService
{
    public function __construct(private ContatoService $contatoService) {}

    /**
     * Cria (ou atualiza) o contato e o inscreve no evento com o ingresso pendente.
     * O pagamento é feito depois na Hotmart, cujo webhook muda o status para "pago".
     *
     * @param  array<string, string|null>  $dados  campos do formulário da Greatpages
     */
    public function registrarInscricao(Evento $evento, array $dados): Inscrito
    {
        $contato = $this->criarOuAtualizarContato($dados);

        // Se o contato já está inscrito no evento, mantém a inscrição como está,
        // para não voltar para "pendente" um ingresso que já foi pago.
        $inscrito = Inscrito::where('evento_id', $evento->id)
            ->where('contato_id', $contato->id)
            ->first();

        if ($inscrito) {
            return $inscrito;
        }

        return Inscrito::create([
            'evento_id' => $evento->id,
            'contato_id' => $contato->id,
            'status_ingresso' => StatusIngresso::Pendente,
            // Plataforma de pagamento: a compra é feita depois na Hotmart.
            'plataforma' => 'hotmart',
            'tipo_ingresso' => 'Social',
            // Quantidade e valor só são conhecidos na compra, e vêm depois pelo webhook da Hotmart.
            'quantidade_ingresso' => null,
            'valor_pago' => null,
            'utm_source' => $dados['utm_source'] ?? null,
            'utm_medium' => $dados['utm_medium'] ?? null,
            'utm_campaign' => $dados['utm_campaign'] ?? null,
            'utm_content' => $dados['utm_content'] ?? null,
        ]);
    }

    /**
     * Cria o contato ou atualiza o que já existe com o mesmo CPF ou e-mail.
     *
     * @param  array<string, string|null>  $dados  campos do formulário da Greatpages
     */
    private function criarOuAtualizarContato(array $dados): Contato
    {
        // CPF sem máscara: "312.048.271-11" => "31204827111".
        $cpf = preg_replace('/\D/', '', (string) ($dados['Digite_Seu_CPF'] ?? '')) ?: null;

        return $this->contatoService->criarOuAtualizar([
            'nome' => $dados['Digite_Seu_Nome'],
            // E-mail em minúsculas, para achar o contato mesmo se vier "Ana@Empresa.com".
            'email' => Str::lower($dados['Digite_Seu_Melhor_E_mail']),
            // Só guarda como CPF se tiver 11 dígitos.
            'cpf' => strlen((string) $cpf) === 11 ? $cpf : null,
            'telefone' => $dados['Digite_Seu_WhatsApp_com_DDD'] ?? null,
            'cargo' => $dados['Qual_o_seu_cargo'] ?? null,
            'negocio' => $dados['Area_de_atuacao_do_seu_negocio'] ?? null,
        ]);
    }
}
