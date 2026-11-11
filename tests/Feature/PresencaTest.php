<?php

use App\Enums\StatusPresenca;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

test('lista os inscritos do próximo evento com os dados do contato', function () {
    $evento = Evento::factory()->create(['titulo' => 'Summit 2026', 'data_evento' => now()->addWeek()]);
    Evento::factory()->create(['data_evento' => now()->addMonths(2)]);
    Inscrito::factory()->for($evento)->for(Contato::factory()->create([
        'nome' => 'Ana Silva',
        'email' => 'ana@example.com',
        'cpf' => '31204827111',
    ]))->create(['quantidade_ingresso' => 2]);

    $this->get(route('presenca.index'))
        ->assertSee('Credenciamento — Summit 2026')
        ->assertSee('Ana Silva')
        ->assertSee('ana@example.com')
        ->assertSee('312.048.271-11')
        ->assertSee('2 ingressos');
});

test('mostra o evento mais recente quando todos já aconteceram', function () {
    Evento::factory()->create(['titulo' => 'Evento Antigo', 'data_evento' => now()->subMonths(3)]);
    Evento::factory()->create(['titulo' => 'Evento Recente', 'data_evento' => now()->subDay()]);

    $this->get(route('presenca.index'))->assertSee('Credenciamento — Evento Recente');
});

test('mostra a lista vazia quando não há eventos', function () {
    $this->get(route('presenca.index'))->assertSee('Nenhum inscrito encontrado.');
});

test('lista só os inscritos do evento escolhido', function () {
    $eventoA = Evento::factory()->create();
    $eventoB = Evento::factory()->create();
    Inscrito::factory()->for($eventoA)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for($eventoB)->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('presenca.index', ['evento' => $eventoB->id]))
        ->assertSee('Carlos Souza')
        ->assertDontSee('Ana Silva');
});

test('busca inscritos pelo nome', function () {
    $evento = Evento::factory()->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('presenca.index', ['evento' => $evento->id, 'busca_nome' => 'Ana']))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('busca inscritos pelo CPF com máscara', function () {
    $evento = Evento::factory()->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Ana Silva', 'cpf' => '31204827111']))->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Carlos Souza', 'cpf' => '41822911984']))->create();

    $this->get(route('presenca.index', ['evento' => $evento->id, 'cpf' => '312.048.271-11']))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('ordena os inscritos pelo nome', function () {
    $evento = Evento::factory()->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();

    $this->get(route('presenca.index', ['evento' => $evento->id]))
        ->assertSeeInOrder(['Ana Silva', 'Carlos Souza']);
});

test('pagina a lista de 15 em 15 mantendo o evento escolhido', function () {
    $evento = Evento::factory()->create();
    Inscrito::factory()->for($evento)->count(16)->create();

    $this->get(route('presenca.index', ['evento' => $evento->id]))
        ->assertSee('page=2');

    $this->get(route('presenca.index', ['evento' => $evento->id, 'page' => 2]))
        ->assertViewHas('inscritos', fn ($inscritos) => $inscritos->count() === 1);
});

test('mostra o botão de confirmar só para quem ainda não está presente', function () {
    $evento = Evento::factory()->create();
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->presente()->create();

    $this->get(route('presenca.index', ['evento' => $evento->id]))
        ->assertSee('Presente')
        ->assertDontSee('Confirmar Presença');
});

test('confirma a presença do inscrito e volta para a página anterior', function () {
    $inscrito = Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    $outro = Inscrito::factory()->create();

    $this->from(route('presenca.index', ['busca_nome' => 'Ana']))
        ->post(route('presenca.store', $inscrito))
        ->assertRedirect(route('presenca.index', ['busca_nome' => 'Ana']))
        ->assertSessionHas('sucesso', 'Presença de Ana Silva confirmada.');

    expect($inscrito->refresh()->status_presenca)->toBe(StatusPresenca::Presente)
        ->and($outro->refresh()->status_presenca)->toBe(StatusPresenca::Pendente);
});

test('responde 404 ao confirmar a presença de um inscrito que não existe', function () {
    $this->post(route('presenca.store', 999))->assertNotFound();
});

test('redireciona para o login quem não está autenticado a ver a lista', function () {
    auth()->logout();

    $this->get(route('presenca.index'))->assertRedirect(route('login'));
});

test('redireciona para o login quem não está autenticado a confirmar presença', function () {
    $inscrito = Inscrito::factory()->create();

    auth()->logout();

    $this->post(route('presenca.store', $inscrito))->assertRedirect(route('login'));

    expect($inscrito->refresh()->status_presenca)->toBe(StatusPresenca::Pendente);
});
