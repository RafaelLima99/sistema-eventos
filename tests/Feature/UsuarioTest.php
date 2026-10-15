<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->logado = criarUsuarioLogado();
});

test('lista os usuários', function () {
    $outro = User::factory()->create(['name' => 'Marina Costa']);

    $this->get(route('usuarios.index'))
        ->assertOk()
        ->assertSee($this->logado->name)
        ->assertSee($outro->name);
});

test('busca usuários pelo nome', function () {
    User::factory()->create(['name' => 'Marina Costa']);
    User::factory()->create(['name' => 'Bruno Alves']);

    $this->get(route('usuarios.index', ['busca_nome' => 'Marina']))
        ->assertOk()
        ->assertSee('Marina Costa')
        ->assertDontSee('Bruno Alves');
});

test('exibe o formulário de novo usuário', function () {
    $this->get(route('usuarios.create'))->assertOk()->assertSee('Novo usuário');
});

test('cria um usuário', function () {
    $this->post(route('usuarios.store'), [
        'name' => 'Marina Costa',
        'email' => 'marina@example.com',
        'password' => 'senha-provisoria',
    ])->assertRedirect(route('usuarios.index'));

    $usuario = User::where('email', 'marina@example.com')->firstOrFail();

    expect($usuario->name)->toBe('Marina Costa')
        ->and(Hash::check('senha-provisoria', $usuario->password))->toBeTrue();
});

test('não cria usuário com e-mail repetido', function () {
    $existente = User::factory()->create();

    $this->post(route('usuarios.store'), [
        'name' => 'Outro',
        'email' => $existente->email,
        'password' => 'senha-provisoria',
    ])->assertSessionHasErrors('email');
});

test('exibe o formulário de edição com os dados do usuário', function () {
    $usuario = User::factory()->create(['name' => 'Marina Costa']);

    $this->get(route('usuarios.edit', $usuario))
        ->assertOk()
        ->assertSee('Marina Costa')
        ->assertSee($usuario->email);
});

test('atualiza um usuário mantendo a senha quando o campo vem vazio', function () {
    $usuario = User::factory()->create();
    $senhaAntiga = $usuario->password;

    $this->put(route('usuarios.update', $usuario), [
        'name' => 'Nome Novo',
        'email' => $usuario->email,
        'password' => '',
    ])->assertRedirect(route('usuarios.index'));

    $usuario->refresh();

    expect($usuario->name)->toBe('Nome Novo')
        ->and($usuario->password)->toBe($senhaAntiga);
});

test('atualiza a senha de um usuário', function () {
    $usuario = User::factory()->create();

    $this->put(route('usuarios.update', $usuario), [
        'name' => $usuario->name,
        'email' => $usuario->email,
        'password' => 'senha-nova-123',
    ])->assertRedirect(route('usuarios.index'));

    expect(Hash::check('senha-nova-123', $usuario->refresh()->password))->toBeTrue();
});

test('remove um usuário', function () {
    $usuario = User::factory()->create();

    $this->delete(route('usuarios.destroy', $usuario))->assertRedirect(route('usuarios.index'));

    $this->assertModelMissing($usuario);
});

test('não remove o próprio usuário', function () {
    $this->delete(route('usuarios.destroy', $this->logado))
        ->assertRedirect(route('usuarios.index'))
        ->assertSessionHas('erro');

    $this->assertModelExists($this->logado);
});

test('visitante não acessa os usuários', function () {
    auth()->logout();

    $this->get(route('usuarios.index'))->assertRedirect(route('login'));
});

test('mostra o resumo da paginação em português', function () {
    User::factory()->count(20)->create();

    $this->get(route('usuarios.index'))
        ->assertOk()
        ->assertSeeInOrder(['Mostrando', '1–15', 'de', '21', 'resultados'], false);
});
