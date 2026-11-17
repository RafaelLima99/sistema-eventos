<?php

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Models\Automacao;
use App\Models\EmailMensagem;
use App\Models\Evento;
use App\Models\WhatsappMensagem;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de automação, com canal WhatsApp.
 *
 * @return array<string, mixed>
 */
function dadosDaAutomacao(array $sobrescrever = []): array
{
    return [
        'titulo' => 'Lembrete de pagamento (15 min)',
        'evento_id' => Evento::factory()->create()->id,
        'horas' => 0,
        'minutos' => 15,
        'filtro' => 'nao_pagantes',
        'canal' => 'whatsapp',
        'whatsapp_mensagem_id' => WhatsappMensagem::factory()->create()->id,
        'email_mensagem_id' => '',
        'status' => 'ativo',
        ...$sobrescrever,
    ];
}

test('lista as automações com o público, a mensagem e o evento', function () {
    Automacao::factory()
        ->for(Evento::factory()->create(['titulo' => 'Summit 2026']))
        ->create([
            'titulo' => 'Lembrete 15 min',
            'minutos_disparo' => 15,
            'whatsapp_mensagem_id' => WhatsappMensagem::factory()->create(['titulo' => 'Mensagem de cobrança'])->id,
        ]);

    $this->get(route('automacoes.index'))
        ->assertOk()
        ->assertSee('Lembrete 15 min')
        ->assertSee('15min após a inscrição')
        ->assertSee('Não pagantes')
        ->assertSee('Mensagem de cobrança')
        ->assertSee('Summit 2026')
        ->assertSee('Ativo');
});

test('mostra a mensagem de e-mail quando o canal é e-mail', function () {
    Automacao::factory()->email()->create([
        'email_mensagem_id' => EmailMensagem::factory()->create(['titulo' => 'Confirmação por e-mail'])->id,
    ]);

    $this->get(route('automacoes.index'))->assertSee('Confirmação por e-mail');
});

test('filtra as automações por evento', function () {
    $evento = Evento::factory()->create();
    Automacao::factory()->for($evento)->create(['titulo' => 'Do evento escolhido']);
    Automacao::factory()->create(['titulo' => 'De outro evento']);

    $this->get(route('automacoes.index', ['evento' => $evento->id]))
        ->assertSee('Do evento escolhido')
        ->assertDontSee('De outro evento');
});

test('pagina as automações de 15 em 15', function () {
    Automacao::factory()->count(16)->create();

    $this->get(route('automacoes.index', ['page' => 2]))
        ->assertViewHas('automacoes', fn ($automacoes) => $automacoes->count() === 1);
});

test('cria uma automação de WhatsApp', function () {
    $dados = dadosDaAutomacao();

    $this->post(route('automacoes.store'), $dados)
        ->assertRedirect(route('automacoes.index'))
        ->assertSessionHas('sucesso', 'Automação criada.');

    expect(Automacao::firstOrFail())
        ->titulo->toBe('Lembrete de pagamento (15 min)')
        ->evento_id->toBe($dados['evento_id'])
        ->minutos_disparo->toBe(15)
        ->filtro->toBe(FiltroPublico::NaoPagantes)
        ->canal->toBe(CanalMensagem::Whatsapp)
        ->whatsapp_mensagem_id->toBe($dados['whatsapp_mensagem_id'])
        ->email_mensagem_id->toBeNull()
        ->status->toBe(StatusAutomacao::Ativo);
});

test('guarda só a mensagem do canal escolhido', function () {
    $emailMensagem = EmailMensagem::factory()->create();

    $this->post(route('automacoes.store'), dadosDaAutomacao([
        'canal' => 'email',
        'email_mensagem_id' => $emailMensagem->id,
    ]))->assertRedirect(route('automacoes.index'));

    expect(Automacao::firstOrFail())
        ->email_mensagem_id->toBe($emailMensagem->id)
        ->whatsapp_mensagem_id->toBeNull();
});

test('salva a automação desativada quando o switch não é marcado', function () {
    $this->post(route('automacoes.store'), dadosDaAutomacao(['status' => 'inativo']));

    expect(Automacao::firstOrFail()->status)->toBe(StatusAutomacao::Inativo);
});

test('não cria a automação sem os campos obrigatórios', function () {
    $this->post(route('automacoes.store'), [])
        ->assertSessionHasErrors(['titulo', 'evento_id', 'horas', 'minutos', 'filtro', 'canal', 'status']);

    expect(Automacao::count())->toBe(0);
});

test('exige a mensagem do canal escolhido', function (string $canal, string $campo, string $erro) {
    $this->post(route('automacoes.store'), dadosDaAutomacao([
        'canal' => $canal,
        'whatsapp_mensagem_id' => '',
        'email_mensagem_id' => '',
    ]))->assertSessionHasErrors([$campo => $erro]);

    expect(Automacao::count())->toBe(0);
})->with([
    'WhatsApp' => ['whatsapp', 'whatsapp_mensagem_id', 'Escolha a mensagem de WhatsApp.'],
    'e-mail' => ['email', 'email_mensagem_id', 'Escolha a mensagem de e-mail.'],
]);

test('junta horas e minutos num total de minutos', function () {
    $this->post(route('automacoes.store'), dadosDaAutomacao(['horas' => 1, 'minutos' => 30]))
        ->assertRedirect(route('automacoes.index'));

    expect(Automacao::firstOrFail()->minutos_disparo)->toBe(90);
});

test('recusa horas e minutos inválidos', function (array $tempo, string $campo) {
    $this->post(route('automacoes.store'), dadosDaAutomacao($tempo))
        ->assertSessionHasErrors($campo);

    expect(Automacao::count())->toBe(0);
})->with([
    'horas negativas' => [['horas' => -1], 'horas'],
    'minutos negativos' => [['minutos' => -5], 'minutos'],
    'minutos acima de 59' => [['minutos' => 60], 'minutos'],
]);

test('mostra o tempo em horas e minutos na listagem', function (int $minutosDisparo, string $esperado) {
    Automacao::factory()->create(['minutos_disparo' => $minutosDisparo]);

    $this->get(route('automacoes.index'))->assertSee($esperado);
})->with([
    'imediato' => [0, 'Logo após a inscrição'],
    'só minutos' => [15, '15min após a inscrição'],
    'só horas' => [120, '2h  após a inscrição'],
    'horas e minutos' => [90, '1h 30min após a inscrição'],
]);

test('mostra o formulário de criação com eventos e mensagens', function () {
    Evento::factory()->create(['titulo' => 'Summit 2026']);
    WhatsappMensagem::factory()->create(['titulo' => 'Mensagem WhatsApp']);
    EmailMensagem::factory()->create(['titulo' => 'Mensagem E-mail']);

    $this->get(route('automacoes.create'))
        ->assertOk()
        ->assertSee('Summit 2026')
        ->assertSee('Mensagem WhatsApp')
        ->assertSee('Mensagem E-mail');
});

test('mostra o formulário de edição com os dados da automação', function () {
    $automacao = Automacao::factory()->create(['titulo' => 'Lembrete de pagamento', 'minutos_disparo' => 90]);

    $this->get(route('automacoes.edit', $automacao))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertSee('name="horas" type="number" min="0" value="1"', false)
        ->assertSee('name="minutos" type="number" min="0" max="59" value="30"', false);
});

test('atualiza a automação trocando o canal e apaga a mensagem do canal antigo', function () {
    $automacao = Automacao::factory()->create();
    $emailMensagem = EmailMensagem::factory()->create();

    $this->put(route('automacoes.update', $automacao), dadosDaAutomacao([
        'titulo' => 'Agora por e-mail',
        'canal' => 'email',
        'email_mensagem_id' => $emailMensagem->id,
    ]))->assertRedirect(route('automacoes.index'))
        ->assertSessionHas('sucesso', 'Automação atualizada.');

    expect($automacao->refresh())
        ->titulo->toBe('Agora por e-mail')
        ->canal->toBe(CanalMensagem::Email)
        ->email_mensagem_id->toBe($emailMensagem->id)
        ->whatsapp_mensagem_id->toBeNull();
});

test('não atualiza a automação com dados inválidos', function () {
    $automacao = Automacao::factory()->create(['titulo' => 'Original']);

    $this->put(route('automacoes.update', $automacao), dadosDaAutomacao(['titulo' => '']))
        ->assertSessionHasErrors('titulo');

    expect($automacao->refresh()->titulo)->toBe('Original');
});

test('remove a automação', function () {
    $automacao = Automacao::factory()->create();

    $this->delete(route('automacoes.destroy', $automacao))
        ->assertRedirect(route('automacoes.index'))
        ->assertSessionHas('sucesso', 'Automação removida.');

    $this->assertModelMissing($automacao);
});

test('responde 404 para uma automação que não existe', function () {
    $this->get(route('automacoes.edit', 999))->assertNotFound();
});

test('visitante não acessa as automações', function () {
    auth()->logout();

    $this->get(route('automacoes.index'))->assertRedirect(route('login'));
});
