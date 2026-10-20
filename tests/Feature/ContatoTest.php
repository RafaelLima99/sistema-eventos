<?php

use App\Models\Contato;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de contato.
 *
 * @return array<string, string>
 */
function dadosDoContato(array $sobrescrever = []): array
{
    return [
        'nome' => 'Ana Silva',
        'email' => 'ana.silva@example.com',
        'telefone' => '(11) 99012-3145',
        'cpf' => '312.048.271-11',
        'cargo' => 'CEO',
        'negocio' => 'Agência',
        ...$sobrescrever,
    ];
}

test('lista os contatos', function () {
    Contato::factory()->create(['nome' => 'Ana Silva', 'cargo' => 'CEO']);

    $this->get(route('contatos.index'))
        ->assertOk()
        ->assertSee('Ana Silva')
        ->assertSee('CEO');
});

test('busca contatos pelo nome', function () {
    Contato::factory()->create(['nome' => 'Ana Silva']);
    Contato::factory()->create(['nome' => 'Carlos Souza']);

    $this->get(route('contatos.index', ['busca_nome' => 'Ana']))
        ->assertOk()
        ->assertSee('Ana Silva')
        ->assertDontSee('Carlos Souza');
});

test('cria um contato salvando o CPF sem máscara', function () {
    $this->post(route('contatos.store'), dadosDoContato())
        ->assertRedirect(route('contatos.index'));

    expect(Contato::firstOrFail()->cpf)->toBe('31204827111');
});

test('valida os campos do contato', function (array $dados, string $campo) {
    Contato::factory()->create(['cpf' => '31204827111']);

    $this->post(route('contatos.store'), dadosDoContato($dados))
        ->assertSessionHasErrors($campo);

    expect(Contato::count())->toBe(1);
})->with([
    'CPF já cadastrado' => [['cpf' => '312.048.271-11'], 'cpf'],
    'CPF incompleto' => [['cpf' => '312.048'], 'cpf'],
]);

test('reabre o modal quando a validação falha', function () {
    $this->from(route('contatos.index'))
        ->followingRedirects()
        ->post(route('contatos.store'), dadosDoContato(['nome' => '']))
        ->assertSee("new bootstrap.Modal(document.getElementById('modalNovoContato')).show()", false);
});

test('exibe os detalhes do contato com o CPF formatado', function () {
    $contato = Contato::factory()->create(['nome' => 'Ana Silva', 'cpf' => '31204827111']);

    $this->get(route('contatos.show', $contato))
        ->assertOk()
        ->assertSee('Ana Silva')
        ->assertSee('312.048.271-11');
});

test('visitante não acessa os contatos', function () {
    auth()->logout();

    $this->get(route('contatos.index'))->assertRedirect(route('login'));
});
