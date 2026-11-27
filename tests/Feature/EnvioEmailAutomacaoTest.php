<?php

use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Enums\StatusIngresso;
use App\Enums\TipoGatilho;
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
        && $request['content'][1]['type'] === 'text/html'
        && str_contains($request['content'][1]['value'], "<p style=\"margin:0 0 16px;\">Olá Ana Silva,<br />\nte esperamos no Summit de Marketing.</p>"));
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

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito, TipoGatilho::AposInscricao);

    Queue::assertPushed(EnviarEmailDaAutomacao::class, fn (EnviarEmailDaAutomacao $job) => $job->automacao->is($automacao) && $job->inscrito->is($inscrito));
});

test('não envia nada quando a automação não é imediata, ativa e do evento (ou é de WhatsApp, ainda não implementado)', function (Closure $criarAutomacao) {
    fingirSendgrid();

    $inscrito = Inscrito::factory()->create();
    $criarAutomacao($inscrito->evento);

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito, TipoGatilho::AposInscricao);

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

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito, TipoGatilho::AposInscricao);

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

test('o e-mail HTML usa o layout, só com o texto da mensagem, links clicáveis e o texto protegido', function () {
    fingirSendgrid();
    config(['mail.from.name' => 'Eventos']);

    $evento = Evento::factory()->create([
        'data_evento' => '2026-12-10',
        'link_pagamento' => 'https://pay.example.com/summit?a=1&b=2',
    ]);
    $inscrito = Inscrito::factory()->create(['evento_id' => $evento->id]);
    $automacao = criarAutomacaoDeEmailImediata($evento, [
        'email_mensagem_id' => EmailMensagem::factory()->create(['conteudo' => "Pague em {{link_pagamento}}\n<b>até amanhã</b>"])->id,
    ]);

    app()->call([new EnviarEmailDaAutomacao($automacao, $inscrito), 'handle']);

    Http::assertSent(function (Request $request) {
        $html = $request['content'][1]['value'];

        return str_contains($html, '<a href="https://pay.example.com/summit?a=1&amp;b=2" style="color:#2563eb; text-decoration:underline;">https://pay.example.com/summit?a=1&amp;b=2</a>')
            && str_contains($html, '&lt;b&gt;até amanhã&lt;/b&gt;') // o "<b>" escrito no texto não vira tag
            && str_contains($html, 'porque se inscreveu em um dos nossos eventos') // rodapé
            && str_contains($html, '© '.now()->year.' Eventos') // rodapé: ano e quem envia
            && ! str_contains($html, '10/12/2026') && ! str_contains($html, 'dezembro'); // sem dados do evento fora do texto
    });
});

test('aceita *negrito* no texto do e-mail', function () {
    fingirSendgrid();

    $evento = Evento::factory()->create();
    $inscrito = Inscrito::factory()->create(['evento_id' => $evento->id]);
    $automacao = criarAutomacaoDeEmailImediata($evento, [
        'email_mensagem_id' => EmailMensagem::factory()->create([
            'conteudo' => "Vaga *garantida*!\nConta: 2 * 3 * 4",
        ])->id,
    ]);

    app()->call([new EnviarEmailDaAutomacao($automacao, $inscrito), 'handle']);

    Http::assertSent(function (Request $request) {
        $html = $request['content'][1]['value'];

        return str_contains($html, 'Vaga <strong>garantida</strong>!')
            // Asteriscos com espaço não viram negrito.
            && str_contains($html, 'Conta: 2 * 3 * 4');
    });
});

test('a automação imediata registra que o inscrito recebeu', function () {
    fingirSendgrid();

    $inscrito = Inscrito::factory()->create();
    $automacao = criarAutomacaoDeEmailImediata($inscrito->evento);

    app(AutomacaoEnvioService::class)->enviarMensagensImediatasSeHouver($inscrito, TipoGatilho::AposInscricao);

    $this->assertDatabaseHas('automacao_envios', ['automacao_id' => $automacao->id, 'inscrito_id' => $inscrito->id]);
});

/*
|--------------------------------------------------------------------------
| Automações agendadas (ex.: 15 min após a inscrição), enviadas pelo agendador
|--------------------------------------------------------------------------
*/

/**
 * Cria uma automação de e-mail para "não pagantes", X minutos após a inscrição.
 */
function criarAutomacaoDeEmailAgendada(Evento $evento, int $minutos = 15, array $sobrescrever = []): Automacao
{
    return Automacao::factory()->email()->create([
        'evento_id' => $evento->id,
        'minutos_disparo' => $minutos,
        'filtro' => FiltroPublico::NaoPagantes,
        ...$sobrescrever,
    ]);
}

/**
 * Roda o que o agendador roda a cada minuto.
 */
function rodarAgendador(): void
{
    app(AutomacaoEnvioService::class)->enviarMensagensAgendadasSeHouver();
}

test('envia o e-mail agendado quando passa o tempo após a inscrição', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    $automacao = criarAutomacaoDeEmailAgendada($evento, 15);
    $inscrito = Inscrito::factory()->create(['evento_id' => $evento->id]);

    $this->travel(14)->minutes();
    rodarAgendador();
    Http::assertNothingSent();

    $this->travel(1)->minutes();
    rodarAgendador();
    Http::assertSentCount(1);

    $this->assertDatabaseHas('automacao_envios', ['automacao_id' => $automacao->id, 'inscrito_id' => $inscrito->id]);
});

test('não envia o e-mail agendado duas vezes para o mesmo inscrito', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailAgendada($evento, 15);
    Inscrito::factory()->create(['evento_id' => $evento->id]);

    $this->travel(15)->minutes();
    rodarAgendador();
    $this->travel(1)->minutes();
    rodarAgendador();

    Http::assertSentCount(1);
});

test('não envia o lembrete de não pagantes para quem pagou antes do horário', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailAgendada($evento, 15);
    $inscrito = Inscrito::factory()->create(['evento_id' => $evento->id]);

    $this->travel(10)->minutes();
    $inscrito->update(['status_ingresso' => StatusIngresso::Pago]);

    $this->travel(5)->minutes();
    rodarAgendador();

    Http::assertNothingSent();
});

test('não envia para quem se inscreveu antes de a automação existir', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    Inscrito::factory()->create(['evento_id' => $evento->id]);

    $this->travel(1)->minutes();
    criarAutomacaoDeEmailAgendada($evento, 15);

    $this->travel(15)->minutes();
    rodarAgendador();

    Http::assertNothingSent();
});

test('não envia com mais de 24h de atraso', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailAgendada($evento, 15);
    Inscrito::factory()->create(['evento_id' => $evento->id]);

    // O agendador ficou parado: só roda 15 min + 25h depois da inscrição.
    $this->travel(15)->minutes();
    $this->travel(25)->hours();
    rodarAgendador();

    Http::assertNothingSent();
});

test('o agendador não envia automações imediatas, inativas nem de WhatsApp (ainda não implementado)', function (Closure $criarAutomacao) {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    $criarAutomacao($evento);
    Inscrito::factory()->create(['evento_id' => $evento->id]);

    $this->travel(30)->minutes();
    rodarAgendador();

    Http::assertNothingSent();
})->with([
    'imediata (0 min, enviada pelo webhook)' => [fn (Evento $evento) => criarAutomacaoDeEmailAgendada($evento, 0)],
    'inativa' => [fn (Evento $evento) => criarAutomacaoDeEmailAgendada($evento, 15, ['status' => StatusAutomacao::Inativo])],
    'de WhatsApp' => [fn (Evento $evento) => Automacao::factory()->create(['evento_id' => $evento->id, 'minutos_disparo' => 15, 'filtro' => FiltroPublico::NaoPagantes])],
]);

/*
|--------------------------------------------------------------------------
| Automações "após o pagamento" (o tempo conta a partir de pago_em)
|--------------------------------------------------------------------------
*/

/**
 * Inscreve a pessoa pela Greatpages (pendente), como no fluxo normal antes do pagamento.
 */
function inscreverPelaGreatpages(Evento $evento): void
{
    config(['services.greatpages.token' => 'token-de-teste']);

    test()->post(route('webhooks.greatpages', $evento->uuid), [
        'Digite_Seu_Nome' => 'Ana Silva',
        'Digite_Seu_Melhor_E_mail' => 'ana@example.com',
    ], ['Authorization' => 'token-de-teste'])->assertOk();
}

/**
 * Avisa o pagamento pela Hotmart, como o webhook de compra aprovada.
 */
function pagarPelaHotmart(Evento $evento): void
{
    config(['services.hotmart.hottok' => 'token-de-teste']);

    test()->postJson(route('webhooks.hotmart', $evento->uuid), [
        'event' => 'PURCHASE_APPROVED',
        'data' => [
            'purchase' => ['price' => ['value' => 35]],
            'buyer' => ['name' => 'Ana Silva', 'email' => 'ana@example.com'],
        ],
    ], ['X-HOTMART-HOTTOK' => 'token-de-teste'])->assertOk();
}

test('Greatpages e depois Hotmart: cada automação imediata é enviada no seu momento, uma vez só', function () {
    fingirSendgrid();

    $evento = Evento::factory()->create();
    $aposInscricao = criarAutomacaoDeEmailImediata($evento);
    $aposPagamento = criarAutomacaoDeEmailImediata($evento, ['tipo_gatilho' => TipoGatilho::AposPagamento, 'filtro' => FiltroPublico::Pagantes]);

    inscreverPelaGreatpages($evento);
    Http::assertSentCount(1);

    pagarPelaHotmart($evento);
    Http::assertSentCount(2);

    $inscrito = Inscrito::firstOrFail();
    $this->assertDatabaseHas('automacao_envios', ['automacao_id' => $aposInscricao->id, 'inscrito_id' => $inscrito->id]);
    $this->assertDatabaseHas('automacao_envios', ['automacao_id' => $aposPagamento->id, 'inscrito_id' => $inscrito->id]);
});

test('não envia de novo quando a Hotmart reenvia o webhook do pagamento', function () {
    fingirSendgrid();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailImediata($evento, ['tipo_gatilho' => TipoGatilho::AposPagamento, 'filtro' => FiltroPublico::Pagantes]);

    inscreverPelaGreatpages($evento);
    pagarPelaHotmart($evento);
    pagarPelaHotmart($evento);

    Http::assertSentCount(1);
});

test('compra direta na Hotmart envia as automações imediatas da inscrição e do pagamento', function () {
    fingirSendgrid();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailImediata($evento);
    criarAutomacaoDeEmailImediata($evento, ['tipo_gatilho' => TipoGatilho::AposPagamento, 'filtro' => FiltroPublico::Pagantes]);

    pagarPelaHotmart($evento);

    Http::assertSentCount(2);
});

test('envia o e-mail agendado quando passa o tempo após o pagamento', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailAgendada($evento, 60, ['tipo_gatilho' => TipoGatilho::AposPagamento, 'filtro' => FiltroPublico::Pagantes]);
    inscreverPelaGreatpages($evento);

    // Pagou 2h depois de se inscrever: o tempo conta a partir do pagamento, não da inscrição.
    $this->travel(2)->hours();
    rodarAgendador();
    pagarPelaHotmart($evento);

    $this->travel(59)->minutes();
    rodarAgendador();
    Http::assertNothingSent();

    $this->travel(1)->minutes();
    rodarAgendador();
    Http::assertSentCount(1);
});

test('o agendador não envia automações "após o pagamento" para quem ainda não pagou', function () {
    fingirSendgrid();
    $this->freezeTime();

    $evento = Evento::factory()->create();
    criarAutomacaoDeEmailAgendada($evento, 15, ['tipo_gatilho' => TipoGatilho::AposPagamento, 'filtro' => FiltroPublico::Pagantes]);
    inscreverPelaGreatpages($evento);

    $this->travel(1)->hours();
    rodarAgendador();

    Http::assertNothingSent();
});

test('o agendador roda o envio das mensagens agendadas a cada minuto', function () {
    $this->artisan('schedule:list')
        ->expectsOutputToContain('enviar-mensagens-agendadas-das-automacoes')
        ->assertSuccessful();
});
