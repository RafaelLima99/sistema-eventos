<?php

use App\Enums\StatusEvento;
use App\Enums\Uf;
use App\Models\Evento;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de evento.
 *
 * @return array<string, string>
 */
function dadosDoEvento(array $sobrescrever = []): array
{
    return [
        'titulo' => 'Summit de Marketing Digital',
        'data_evento' => '2026-12-10',
        'hora_inicio' => '09:00',
        'hora_fim' => '18:00',
        'local' => 'Centro de Convenções',
        'preco_ingresso' => '1.200,50',
        'endereco' => 'R. Frei Caneca, 569',
        'cidade' => 'São Paulo',
        'uf' => 'SP',
        'link_inscricao' => 'https://inscrever.example.com/summit',
        'link_pagamento' => 'https://pay.example.com/summit',
        ...$sobrescrever,
    ];
}

test('lista os eventos', function () {
    $evento = Evento::factory()->publicado()->create(['titulo' => 'Meetup IA']);

    $this->get(route('eventos.index'))
        ->assertOk()
        ->assertSee('Meetup IA')
        ->assertSee('Publicado')
        ->assertSee($evento->data_evento->format('d/m/Y'));
});

test('busca eventos pelo nome', function () {
    Evento::factory()->create(['titulo' => 'Meetup IA']);
    Evento::factory()->create(['titulo' => 'Workshop de Vendas']);

    $this->get(route('eventos.index', ['busca_nome' => 'Meetup']))
        ->assertOk()
        ->assertSee('Meetup IA')
        ->assertDontSee('Workshop de Vendas');
});

test('cria um evento como publicado', function () {
    $this->post(route('eventos.store'), dadosDoEvento())
        ->assertSessionHasNoErrors();

    $evento = Evento::where('titulo', 'Summit de Marketing Digital')->firstOrFail();

    expect($evento->preco_ingresso)->toBe('1200.50')
        ->and($evento->uf)->toBe(Uf::Sp)
        ->and($evento->status)->toBe(StatusEvento::Publicado);
});

test('valida os campos do evento', function (array $dados, string $campo) {
    $this->post(route('eventos.store'), dadosDoEvento($dados))
        ->assertSessionHasErrors($campo);

    expect(Evento::count())->toBe(0);
})->with([
    'UF inválida' => [['uf' => 'XX'], 'uf'],
    'término antes do início' => [['hora_inicio' => '18:00', 'hora_fim' => '09:00'], 'hora_fim'],
    'valor negativo' => [['preco_ingresso' => '-10'], 'preco_ingresso'],
    'link que não é http' => [['link_inscricao' => 'javascript:alert(1)'], 'link_inscricao'],
]);

test('exibe os detalhes do evento', function () {
    $evento = Evento::factory()->create([
        'titulo' => 'Meetup IA',
        'data_evento' => '2026-09-18',
        'preco_ingresso' => 600,
    ]);

    $this->get(route('eventos.show', $evento))
        ->assertOk()
        ->assertSee('Meetup IA')
        ->assertSee('18/09/2026 (sexta-feira)')
        ->assertSee('R$ 600,00');
});

test('exibe a URL do webhook da Hotmart com o UUID do evento', function () {
    $evento = Evento::factory()->create();

    $this->get(route('eventos.show', $evento))
        ->assertOk()
        ->assertSee(url("/webhooks/hotmart/eventos/{$evento->uuid}"));
});

test('exibe as horas do evento sem os segundos', function () {
    $evento = Evento::factory()->create(['hora_inicio' => '09:00:00', 'hora_fim' => '18:30:00']);

    expect($evento->refresh()->hora_inicio)->toBe('09:00')
        ->and($evento->hora_fim)->toBe('18:30');

    $this->get(route('eventos.index'))->assertSee('09:00 – 18:30');
});

test('exibe o formulário de edição com os dados do evento', function () {
    $evento = Evento::factory()->create(['titulo' => 'Meetup IA', 'preco_ingresso' => 1200.5]);

    $this->get(route('eventos.edit', $evento))
        ->assertOk()
        ->assertSee('Meetup IA')
        ->assertSee('1.200,50');
});

test('atualiza um evento', function () {
    $evento = Evento::factory()->create();

    $this->put(route('eventos.update', $evento), dadosDoEvento(['titulo' => 'Título Novo']))
        ->assertRedirect(route('eventos.index'));

    expect($evento->refresh()->titulo)->toBe('Título Novo');
});

test('remove um evento', function () {
    $evento = Evento::factory()->create();

    $this->delete(route('eventos.destroy', $evento))->assertRedirect(route('eventos.index'));

    $this->assertModelMissing($evento);
});

test('visitante não acessa os eventos', function () {
    auth()->logout();

    $this->get(route('eventos.index'))->assertRedirect(route('login'));
});
