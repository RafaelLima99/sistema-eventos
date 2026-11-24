<?php

use App\Enums\FiltroPublico;
use App\Jobs\EnviarEmailDaAutomacao;
use App\Models\Automacao;
use App\Models\Contato;
use App\Models\EmailMensagem;
use App\Models\Evento;
use App\Models\Inscrito;
use App\Services\AutomacaoEnvioService;
use App\Services\EmailVariaveisService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

/**
 * Finge a API do SendGrid respondendo "aceito" (202), como faz quando o e-mail entra na fila de envio dela.
 */
function fingirSendgrid(): void
{
    Http::fake(['api.sendgrid.com/v3/mail/send' => Http::response(null, 202)]);
}

/**
 * Cria uma automação de e-mail imediata ("Logo após a inscrição") no evento.
 */
function criarAutomacaoDeEmailImediata(Evento $evento, array $sobrescrever = []): Automacao
{
    return Automacao::factory()->email()->create([
        'evento_id' => $evento->id,
        'minutos_disparo' => 0,
        'filtro' => FiltroPublico::SemFiltro,
        ...$sobrescrever,
    ]);
}

test('inscrição pela Greatpages envia na hora o e-mail da automação, com as variáveis preenchidas', function () {
    fingirSendgrid();
    config(['services.greatpages.token' => 'token-de-teste', 'mail.from.address' => 'contato@eventos.test', 'mail.from.name' => 'Eventos']);

    $evento = Evento::factory()->create(['titulo' => 'Summit de Marketing']);
    criarAutomacaoDeEmailImediata($evento, [
        'email_mensagem_id' => EmailMensagem::factory()->create([
            'assunto' => 'Inscrição confirmada — {{evento}}',
            'conteudo' => "Olá {{nome}},\nte esperamos no {{ evento }}.",
        ])->id,
    ]);

    $this->post(route('webhooks.greatpages', $evento->uuid), [
        'Digite_Seu_Nome' => 'Ana Silva',
        'Digite_Seu_Melhor_E_mail' => 'ana@example.com',
    ], ['Authorization' => 'token-de-teste'])->assertOk();

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.sendgrid.com/v3/mail/send'
        && $request->hasHeader('Authorization', 'Bearer chave-de-teste')
        && $request['personalizations'][0]['to'][0] === ['email' => 'ana@example.com', 'name' => 'Ana Silva']
        && $request['from'] === ['email' => 'contato@eventos.test', 'name' => 'Eventos']
        && $request['subject'] === 'Inscrição confirmada — Summit de Marketing'
        && $request['content'][0] === ['type' => 'text/plain', 'value' => "Olá Ana Silva,\nte esperamos no Summit de Marketing."]
        && $request['content'][1] === ['type' => 'text/html', 'value' => "Olá Ana Silva,<br />\nte esperamos no Summit de Marketing."]);
});

test('compra aprovada na Hotmart envia o e-mail da automação para pagantes', function () {
    fingirSendgrid();
    config(['services.hotmart.hottok' => 'token-de-teste']);

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailImediata($evento, ['filtro' => FiltroPublico::Pagantes]);

    $this->postJson(route('webhooks.hotmart', $evento->uuid), [
        'event' => 'PURCHASE_APPROVED',
        'data' => [
            'purchase' => ['price' => ['value' => 35]],
            'buyer' => ['name' => 'Ana Silva', 'email' => 'ana@example.com'],
        ],
    ], ['X-HOTMART-HOTTOK' => 'token-de-teste'])->assertOk();

    Http::assertSentCount(1);
});

test('envia o e-mail pela fila, fora do webhook', function () {
    Queue::fake([EnviarEmailDaAutomacao::class]);

    $inscrito = Inscrito::factory()->create();
    $automacao = criarAutomacaoDeEmailImediata($inscrito->evento);

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito);

    Queue::assertPushed(EnviarEmailDaAutomacao::class, fn (EnviarEmailDaAutomacao $job) => $job->automacao->is($automacao) && $job->inscrito->is($inscrito));
});

test('não envia nada quando a automação não é imediata, ativa e do evento (ou é de WhatsApp, ainda não implementado)', function (Closure $criarAutomacao) {
    fingirSendgrid();

    $inscrito = Inscrito::factory()->create();
    $criarAutomacao($inscrito->evento);

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito);

    Http::assertNothingSent();
})->with([
    'com tempo (2h após a inscrição)' => [fn (Evento $evento) => criarAutomacaoDeEmailImediata($evento, ['minutos_disparo' => 120])],
    'inativa' => [fn (Evento $evento) => Automacao::factory()->email()->inativo()->create(['evento_id' => $evento->id, 'minutos_disparo' => 0, 'filtro' => FiltroPublico::SemFiltro])],
    'de WhatsApp' => [fn (Evento $evento) => Automacao::factory()->create(['evento_id' => $evento->id, 'minutos_disparo' => 0, 'filtro' => FiltroPublico::SemFiltro])],
    'de outro evento' => [fn (Evento $evento) => criarAutomacaoDeEmailImediata(Evento::factory()->create())],
]);

test('envia só para o público escolhido na automação', function (FiltroPublico $filtro, bool $envia) {
    fingirSendgrid();

    // Inscrição pendente (ainda não pagou), como chega pela Greatpages.
    $inscrito = Inscrito::factory()->create();
    criarAutomacaoDeEmailImediata($inscrito->evento, ['filtro' => $filtro]);

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito);

    Http::assertSentCount($envia ? 1 : 0);
})->with([
    'toda a base' => [FiltroPublico::SemFiltro, true],
    'não pagantes' => [FiltroPublico::NaoPagantes, true],
    'pagantes' => [FiltroPublico::Pagantes, false],
    'presentes' => [FiltroPublico::Presentes, false],
    'ausentes' => [FiltroPublico::Ausentes, false],
]);

test('não envia de novo quando o contato já estava inscrito no evento', function () {
    fingirSendgrid();
    config(['services.greatpages.token' => 'token-de-teste']);

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailImediata($evento);
    Inscrito::factory()->create([
        'evento_id' => $evento->id,
        'contato_id' => Contato::factory()->create(['email' => 'ana@example.com'])->id,
    ]);

    $this->post(route('webhooks.greatpages', $evento->uuid), [
        'Digite_Seu_Nome' => 'Ana Silva',
        'Digite_Seu_Melhor_E_mail' => 'ana@example.com',
    ], ['Authorization' => 'token-de-teste'])->assertOk();

    Http::assertNothingSent();
});

test('o job falha quando o SendGrid recusa o envio, para a fila tentar de novo', function () {
    Http::fake(['api.sendgrid.com/v3/mail/send' => Http::response(['errors' => [['message' => 'The provided authorization grant is invalid']]], 401)]);

    $inscrito = Inscrito::factory()->create();
    $automacao = criarAutomacaoDeEmailImediata($inscrito->evento);

    app()->call([new EnviarEmailDaAutomacao($automacao, $inscrito), 'handle']);
})->throws(RequestException::class);

test('preenche as variáveis com os dados do inscrito e do evento', function () {
    $evento = Evento::factory()->create([
        'titulo' => 'Summit',
        'data_evento' => '2026-12-10',
        'hora_inicio' => '09:00',
        'local' => 'Centro de Convenções',
        'cidade' => 'Recife',
        'uf' => 'PE',
        'link_pagamento' => null,
    ]);
    $inscrito = Inscrito::factory()->create([
        'evento_id' => $evento->id,
        'contato_id' => Contato::factory()->create(['nome' => 'Ana'])->id,
        'tipo_ingresso' => 'VIP',
    ]);

    $texto = app(EmailVariaveisService::class)->preencher(
        '{{nome}} | {{evento}} | {{data}} | {{horario}} | {{localidade}} | {{tipo_ingresso}} | {{link_pagamento}}',
        $inscrito,
    );

    expect($texto)->toBe('Ana | Summit | 10/12/2026 | 09:00 | Centro de Convenções – Recife/PE | VIP | ');
});
