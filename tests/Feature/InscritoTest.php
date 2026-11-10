<?php

use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

test('lista os inscritos com os dados do contato e do evento', function () {
    Inscrito::factory()
        ->for(Evento::factory()->create(['titulo' => 'Summit 2026']))
        ->for(Contato::factory()->create(['nome' => 'Ana Silva', 'cpf' => '31204827111']))
        ->pago()
        ->create(['tipo_ingresso' => 'VIP', 'valor_pago' => 1200]);

    $this->get(route('inscritos.index'))
        ->assertSee('Ana Silva')
        ->assertSee('312.048.271-11')
        ->assertSee('Summit 2026')
        ->assertSee('Pago')
        ->assertSee('VIP')
        ->assertSee('R$ 1.200,00');
});

test('mostra traço no lugar dos dados da compra que ainda não existem', function () {
    Inscrito::factory()->semDadosDaCompra()->create();

    $this->get(route('inscritos.index'))
        ->assertSee('—')
        ->assertDontSee('R$');
});

test('busca inscritos pelo nome do contato', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('inscritos.index', ['busca_nome' => 'Ana']))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('filtra inscritos por evento', function () {
    $eventoA = Evento::factory()->create();
    Inscrito::factory()->for($eventoA)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('inscritos.index', ['evento' => $eventoA->id]))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('filtra inscritos por CPF com ou sem máscara', function (string $cpf) {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva', 'cpf' => '31204827111']))->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza', 'cpf' => '41822911984']))->create();

    $this->get(route('inscritos.index', ['cpf' => $cpf]))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
})->with([
    'com máscara' => '312.048.271-11',
    'sem máscara' => '31204827111',
]);

test('filtra inscritos por status do ingresso', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->pago()->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('inscritos.index', ['status_ingresso' => 'pago']))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('filtra inscritos por presença', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->presente()->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $this->get(route('inscritos.index', ['status_presenca' => 'presente']))
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('exclui a inscrição sem apagar o contato nem o evento', function () {
    $inscrito = Inscrito::factory()->create();

    $this->delete(route('inscritos.destroy', $inscrito))
        ->assertRedirect(route('inscritos.index'))
        ->assertSessionHas('sucesso', 'Inscrito removido.');

    $this->assertModelMissing($inscrito);
    $this->assertModelExists($inscrito->contato);
    $this->assertModelExists($inscrito->evento);
});

test('responde 404 ao excluir uma inscrição que não existe', function () {
    $this->delete(route('inscritos.destroy', 999))->assertNotFound();
});

test('redireciona para o login quem não está autenticado a listar', function () {
    auth()->logout();

    $this->get(route('inscritos.index'))->assertRedirect(route('login'));
});

test('redireciona para o login quem não está autenticado a excluir', function () {
    $inscrito = Inscrito::factory()->create();

    auth()->logout();

    $this->delete(route('inscritos.destroy', $inscrito))->assertRedirect(route('login'));

    $this->assertModelExists($inscrito);
});
