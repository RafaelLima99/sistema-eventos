<?php

use App\Models\User;

test('visitante é redirecionado para o login', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('tela de login é exibida', function () {
    $this->get(route('login'))->assertOk()->assertSee('Entrar');
});

test('usuário entra com credenciais válidas', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('senha incorreta não autentica', function () {
    $user = User::factory()->create();

    $this->from(route('login'))->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'senha-errada',
    ])->assertRedirect(route('login'))
        ->assertSessionHas('erro', 'E-mail ou senha inválidos.')
        ->assertSessionHasNoErrors();

    $this->assertGuest();
});

test('usuário logado não acessa a tela de login', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));
});

test('usuário sai do sistema', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
