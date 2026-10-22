<?php

use App\Enums\StatusIngresso;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Testing\TestResponse;

beforeEach(function () {
    config(['services.hotmart.hottok' => 'token-de-teste']);

    $this->evento = Evento::factory()->create();
});

/**
 * Webhook de compra aprovada no formato da Hotmart (versão 2.0.0), com dados fictícios.
 *
 * @return array<string, mixed>
 */
function webhookHotmart(array $sobrescrever = []): array
{
    return array_replace_recursive([
        'id' => 'b5b070e7-0000-0000-0000-000000000000',
        'event' => 'PURCHASE_APPROVED',
        'version' => '2.0.0',
        'data' => [
            'product' => ['id' => 123, 'name' => 'Evento de teste'],
            'purchase' => [
                'transaction' => 'HP0000000001',
                'status' => 'APPROVED',
                'offer' => ['code' => 'oferta01'],
                'event_tickets' => ['amount' => 2],
                'price' => ['currency_value' => 'BRL', 'value' => 35],
            ],
            'buyer' => [
                'name' => 'Ana Silva',
                'email' => 'Ana.Silva@Example.com',
                'document' => '31204827111',
                'document_type' => 'CPF',
                'checkout_phone' => '81999990000',
            ],
        ],
    ], $sobrescrever);
}

/**
 * Envia o webhook para o link do evento, com o hottok informado.
 */
function enviarWebhook(Evento $evento, array $dados, string $hottok = 'token-de-teste'): TestResponse
{
    return test()->postJson(route('webhooks.hotmart', $evento->uuid), $dados, ['X-HOTMART-HOTTOK' => $hottok]);
}

test('compra aprovada cria o contato e a inscrição no evento do link', function () {
    enviarWebhook($this->evento, webhookHotmart())->assertOk();

    $contato = Contato::firstOrFail();
    $inscrito = Inscrito::firstOrFail();

    expect($contato)
        ->nome->toBe('Ana Silva')
        ->email->toBe('ana.silva@example.com')
        ->cpf->toBe('31204827111')
        ->telefone->toBe('81999990000');

    expect($inscrito)
        ->evento_id->toBe($this->evento->id)
        ->contato_id->toBe($contato->id)
        ->status_ingresso->toBe(StatusIngresso::Pago)
        ->plataforma->toBe('hotmart')
        ->tipo_ingresso->toBe('oferta01')
        ->quantidade_ingresso->toBe(2)
        ->valor_pago->toBe('35.00');
});

test('usa o contato que já existe com o mesmo CPF', function () {
    $contato = Contato::factory()->create(['cpf' => '31204827111', 'email' => 'outro@example.com']);

    enviarWebhook($this->evento, webhookHotmart())->assertOk();

    expect(Contato::count())->toBe(1)
        ->and(Inscrito::firstOrFail()->contato_id)->toBe($contato->id);
});

test('contato já inscrito só tem o status do ingresso atualizado para pago', function () {
    $contato = Contato::factory()->create(['cpf' => '31204827111']);
    $inscrito = Inscrito::create([
        'evento_id' => $this->evento->id,
        'contato_id' => $contato->id,
        'status_ingresso' => StatusIngresso::Pendente,
        'tipo_ingresso' => 'Inteira',
        'quantidade_ingresso' => 1,
        'valor_pago' => 0,
    ]);

    enviarWebhook($this->evento, webhookHotmart())->assertOk();

    expect(Inscrito::count())->toBe(1)
        ->and($inscrito->refresh())
        ->status_ingresso->toBe(StatusIngresso::Pago)
        ->tipo_ingresso->toBe('Inteira')
        ->quantidade_ingresso->toBe(1)
        ->valor_pago->toBe('0.00');
});

test('não duplica a inscrição quando a Hotmart reenvia o mesmo webhook', function () {
    enviarWebhook($this->evento, webhookHotmart())->assertOk();
    enviarWebhook($this->evento, webhookHotmart())->assertOk();

    expect(Inscrito::count())->toBe(1)
        ->and(Inscrito::firstOrFail()->quantidade_ingresso)->toBe(2);
});

test('recusa a chamada com hottok errado', function () {
    enviarWebhook($this->evento, webhookHotmart(), 'token-errado')->assertForbidden();

    expect(Inscrito::count())->toBe(0);
});

test('recusa a chamada quando o hottok não está configurado', function () {
    config(['services.hotmart.hottok' => null]);

    enviarWebhook($this->evento, webhookHotmart(), '')->assertForbidden();
});

test('responde 404 para um evento que não existe', function () {
    $this->postJson(route('webhooks.hotmart', 'uuid-inexistente'), webhookHotmart(), ['X-HOTMART-HOTTOK' => 'token-de-teste'])
        ->assertNotFound();
});

test('ignora outros eventos da Hotmart', function () {
    enviarWebhook($this->evento, ['event' => 'PURCHASE_REFUNDED', 'data' => []])
        ->assertOk()
        ->assertJson(['status' => 'ignorado']);

    expect(Inscrito::count())->toBe(0);
});

test('recusa compra aprovada sem e-mail do comprador', function () {
    $dados = webhookHotmart();
    unset($dados['data']['buyer']['email']);

    enviarWebhook($this->evento, $dados)->assertUnprocessable();

    expect(Inscrito::count())->toBe(0);
});
