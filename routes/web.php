<?php

use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->name('login');

Route::view('/', 'dashboard')->name('dashboard');
Route::view('/presenca', 'presenca.index')->name('presenca.index');
Route::view('/configuracoes', 'configuracoes.index')->name('configuracoes.index');

// Eventos
Route::view('/eventos', 'eventos.index')->name('eventos.index');
Route::view('/eventos/create', 'eventos.create')->name('eventos.create');
Route::view('/eventos/{id}', 'eventos.show')->name('eventos.show');
Route::view('/eventos/{id}/edit', 'eventos.edit')->name('eventos.edit');

// Inscritos (inscritos.show: view ainda não existe no protótipo)
Route::view('/inscritos', 'inscritos.index')->name('inscritos.index');
Route::view('/inscritos/create', 'inscritos.create')->name('inscritos.create');
Route::view('/inscritos/{id}', 'inscritos.show')->name('inscritos.show');

// Contatos
Route::view('/contatos', 'contatos.index')->name('contatos.index');
Route::view('/contatos/{id}', 'contatos.show')->name('contatos.show');

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
Route::view('/usuarios', 'usuarios.index')->name('usuarios.index');
Route::view('/usuarios/create', 'usuarios.create')->name('usuarios.create');
Route::view('/usuarios/{id}/edit', 'usuarios.edit')->name('usuarios.edit');

