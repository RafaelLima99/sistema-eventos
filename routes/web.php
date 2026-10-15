<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::view('/', 'dashboard')->name('dashboard');
    Route::view('/presenca', 'presenca.index')->name('presenca.index');
    Route::view('/configuracoes', 'configuracoes.index')->name('configuracoes.index');

    // Eventos
    Route::resource('eventos', EventoController::class);

    // Inscritos (inscritos.show: view ainda não existe no protótipo)
    Route::view('/inscritos', 'inscritos.index')->name('inscritos.index');
    Route::view('/inscritos/create', 'inscritos.create')->name('inscritos.create');
    Route::view('/inscritos/{id}', 'inscritos.show')->name('inscritos.show');

    // Contatos
    Route::resource('contatos', ContatoController::class)->only(['index', 'store', 'show']);

    // Automações (gatilhos)
    Route::view('/automacoes', 'automacoes.index')->name('automacoes.index');
    Route::view('/automacoes/create', 'automacoes.create')->name('automacoes.create');
    Route::view('/automacoes/{id}/edit', 'automacoes.edit')->name('automacoes.edit');

    // Mensagens
    Route::view('/whatsapp-mensagens', 'whatsapp-mensagens.index')->name('whatsapp-mensagens.index');
    Route::view('/whatsapp-mensagens/create', 'whatsapp-mensagens.create')->name('whatsapp-mensagens.create');
    Route::view('/whatsapp-mensagens/{id}', 'whatsapp-mensagens.show')->name('whatsapp-mensagens.show');
    Route::view('/whatsapp-mensagens/{id}/edit', 'whatsapp-mensagens.edit')->name('whatsapp-mensagens.edit');

    Route::view('/email-mensagens', 'email-mensagens.index')->name('email-mensagens.index');
    Route::view('/email-mensagens/create', 'email-mensagens.create')->name('email-mensagens.create');
    Route::view('/email-mensagens/{id}', 'email-mensagens.show')->name('email-mensagens.show');
    Route::view('/email-mensagens/{id}/edit', 'email-mensagens.edit')->name('email-mensagens.edit');

    // Disparos
    Route::view('/disparos', 'disparos.index')->name('disparos.index');
    Route::view('/disparos/create', 'disparos.create')->name('disparos.create');
    Route::view('/disparos/{id}/edit', 'disparos.edit')->name('disparos.edit');

    // Usuários
    Route::resource('usuarios', UserController::class)->except('show');
});
