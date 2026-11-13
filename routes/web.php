<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\ContatoImportacaoController;
use App\Http\Controllers\EmailMensagemController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscritoController;
use App\Http\Controllers\InscritoExportacaoController;
use App\Http\Controllers\PresencaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatsappMensagemController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::view('/', 'dashboard')->name('dashboard');
    // Presença
    Route::get('/presenca', [PresencaController::class, 'index'])->name('presenca.index');
    Route::post('/presenca/{inscrito}', [PresencaController::class, 'store'])->name('presenca.store');
    Route::view('/configuracoes', 'configuracoes.index')->name('configuracoes.index');

    // Eventos
    Route::resource('eventos', EventoController::class);

    // Inscritos (inscritos.show: view ainda não existe no protótipo)
    Route::get('/inscritos', [InscritoController::class, 'index'])->name('inscritos.index');
    Route::get('/inscritos/exportacao', [InscritoExportacaoController::class, 'index'])->name('inscritos.exportacao.index');
    Route::view('/inscritos/create', 'inscritos.create')->name('inscritos.create');
    Route::view('/inscritos/{id}', 'inscritos.show')->name('inscritos.show');
    Route::delete('/inscritos/{inscrito}', [InscritoController::class, 'destroy'])->name('inscritos.destroy');

    // Contatos
    Route::post('/contatos/importacao', [ContatoImportacaoController::class, 'store'])->name('contatos.importacao.store');
    Route::resource('contatos', ContatoController::class)->only(['index', 'store', 'show']);

    // Automações (gatilhos)
    Route::view('/automacoes', 'automacoes.index')->name('automacoes.index');
    Route::view('/automacoes/create', 'automacoes.create')->name('automacoes.create');
    Route::view('/automacoes/{id}/edit', 'automacoes.edit')->name('automacoes.edit');

    // Mensagens
    // parameters(): nome do parâmetro da rota, igual ao do controller ($whatsappMensagem), para o Laravel achar o model sozinho.
    Route::resource('whatsapp-mensagens', WhatsappMensagemController::class)
        ->parameters(['whatsapp-mensagens' => 'whatsappMensagem']);

    // parameters(): nome do parâmetro da rota, igual ao do controller ($emailMensagem), para o Laravel achar o model sozinho.
    Route::resource('email-mensagens', EmailMensagemController::class)
        ->parameters(['email-mensagens' => 'emailMensagem']);

    // Disparos
    Route::view('/disparos', 'disparos.index')->name('disparos.index');
    Route::view('/disparos/create', 'disparos.create')->name('disparos.create');
    Route::view('/disparos/{id}/edit', 'disparos.edit')->name('disparos.edit');

    // Usuários
    Route::resource('usuarios', UserController::class)->except('show');
});
