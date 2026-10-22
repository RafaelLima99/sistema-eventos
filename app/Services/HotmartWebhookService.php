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
     * Se o contato já está inscrito no evento, só o status do ingresso muda para "pago".
     * Isso também evita duplicar quando a Hotmart reenvia o mesmo webhook.
     *
     * @param  array<string, mixed>  $dados  conteúdo de "data" do webhook
     */
    public function registrarCompraAprovada(Evento $evento, array $dados): Inscrito
    {
        $contato = $this->salvarContato($dados['buyer']);

        $inscrito = Inscrito::where('evento_id', $evento->id)
            ->where('contato_id', $contato->id)
            ->first();

        // Se o contato já está inscrito no evento, só atualiza o status do ingresso para pago.
        if ($inscrito) {
            
            $inscrito->update(['status_ingresso' => StatusIngresso::Pago]);

            return $inscrito;
        }

        return Inscrito::create([
            'evento_id' => $evento->id,
            'contato_id' => $contato->id,
            'status_ingresso' => StatusIngresso::Pago,
            'plataforma' => 'hotmart',
            // A Hotmart não envia o nome da oferta, só o código (ex.: "muoy99hp").
            'tipo_ingresso' => data_get($dados, 'purchase.offer.code') ?? 'hotmart',
            'quantidade_ingresso' => data_get($dados, 'purchase.event_tickets.amount') ?? 1,
            'valor_pago' => data_get($dados, 'purchase.price.value'),
        ]);
    }

    /**
     * Cria o contato do comprador ou atualiza o que já existe com o mesmo CPF ou e-mail.
     *
     * @param  array<string, mixed>  $comprador  conteúdo de "data.buyer" do webhook
     */
    private function salvarContato(array $comprador): Contato
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
