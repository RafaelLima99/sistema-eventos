<?php

namespace App\Services;

use App\Enums\StatusIngresso;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Support\Str;

/**
 * Transforma uma compra aprovada da Hotmart em uma inscrição no evento.
 */
class HotmartWebhookService
{
    public function __construct(private ContatoService $contatoService) {}

    /**
     * Cria (ou atualiza) o contato do comprador e o inscreve no evento.
     *
     * Se o contato já está inscrito no evento (ex.: pelo formulário da Greatpages), a inscrição
     * é completada com os dados da compra. Isso também evita duplicar quando a Hotmart reenvia o webhook.
     *
     * @param  array<string, mixed>  $dados  conteúdo de "data" do webhook
     */
    public function registrarCompraAprovada(Evento $evento, array $dados): Inscrito
    {
        $contato = $this->criarOuAtualizarContato($dados['buyer']);

        // O tipo de ingresso não vem da Hotmart: ele é definido pelo formulário da Greatpages.
        $dadosDaCompra = [
            'status_ingresso' => StatusIngresso::Pago,
            'quantidade_ingresso' => data_get($dados, 'purchase.event_tickets.amount') ?? 1,
            'valor_pago' => data_get($dados, 'purchase.price.value'),
        ];

        $inscrito = Inscrito::where('evento_id', $evento->id)
            ->where('contato_id', $contato->id)
            ->first();

        // Se o contato já está inscrito no evento, completa a inscrição com os dados da compra.
        if ($inscrito) {
            $inscrito->update($dadosDaCompra);

            return $inscrito;
        }

        return Inscrito::create([
            'evento_id' => $evento->id,
            'contato_id' => $contato->id,
            'plataforma' => 'hotmart',
            // Comprou direto pela Hotmart, sem passar pelo formulário da Greatpages.
            'tipo_ingresso' => null,
            ...$dadosDaCompra,
        ]);
    }

    /**
     * Cria o contato do comprador ou atualiza o que já existe com o mesmo CPF ou e-mail.
     *
     * @param  array<string, mixed>  $comprador  conteúdo de "data.buyer" do webhook
     */
    private function criarOuAtualizarContato(array $comprador): Contato
    {
        // CPF sem máscara: "312.048.271-11" => "31204827111".
        $cpf = preg_replace('/\D/', '', (string) ($comprador['document'] ?? '')) ?: null;

        // E-mail em minúsculas, para achar o contato mesmo se vier "Ana@Empresa.com".
        $email = Str::lower($comprador['email']);

        return $this->contatoService->criarOuAtualizar([
            'nome' => $comprador['name'],
            'email' => $email,
            // Só guarda como CPF se tiver 11 dígitos (compradores estrangeiros enviam outros documentos).
            'cpf' => strlen((string) $cpf) === 11 ? $cpf : null,
            'telefone' => $comprador['checkout_phone'] ?? null,
        ]);
    }
}
