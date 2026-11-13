<?php

use App\Models\Automacao;
use App\Models\EmailMensagem;
use App\Models\Evento;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de mensagem de e-mail.
 *
 * @return array<string, string>
 */
function dadosDaMensagemDeEmail(array $sobrescrever = []): array
{
    return [
        'titulo' => 'Confirmação de inscrição · E-mail',
        'assunto' => 'Inscrição confirmada — {{evento}}',
        'conteudo' => "Olá {{nome}},\n\nSua inscrição no {{evento}} foi confirmada.",
        ...$sobrescrever,
    ];
}

test('lista as mensagens de e-mail', function () {
    EmailMensagem::factory()->create(['titulo' => 'Lembrete de pagamento']);

    $this->get(route('email-mensagens.index'))
        ->assertOk()
        ->assertSee('Lembrete de pagamento');
});

test('busca mensagens pelo título', function () {
    EmailMensagem::factory()->create(['titulo' => 'Lembrete de pagamento']);
    EmailMensagem::factory()->create(['titulo' => 'Pós-evento']);

    $this->get(route('email-mensagens.index', ['busca_nome' => 'Lembrete']))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertDontSee('Pós-evento');
});

test('pagina as mensagens de 15 em 15', function () {
    EmailMensagem::factory()->count(16)->create();

    $this->get(route('email-mensagens.index', ['page' => 2]))
        ->assertViewHas('emailMensagens', fn ($mensagens) => $mensagens->count() === 1);
});

test('mostra os detalhes da mensagem com as variáveis usadas', function () {
    $mensagem = EmailMensagem::factory()->create([
        'titulo' => 'Confirmação',
        'assunto' => 'Inscrição em {{evento}}',
        'conteudo' => 'Olá {{nome}}',
    ]);

    $this->get(route('email-mensagens.show', $mensagem))
        ->assertOk()
        ->assertSee('Confirmação')
        ->assertSee('Inscrição em {{evento}}', false)
        ->assertSee('Olá {{nome}}', false)
        ->assertSee('{{nome}}', false);
});

test('cria uma mensagem de e-mail', function () {
    $this->post(route('email-mensagens.store'), dadosDaMensagemDeEmail())
        ->assertRedirect(route('email-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem criada.');

    expect(EmailMensagem::firstOrFail())
        ->titulo->toBe('Confirmação de inscrição · E-mail')
        ->assunto->toBe('Inscrição confirmada — {{evento}}')
        ->conteudo->toBe("Olá {{nome}},\n\nSua inscrição no {{evento}} foi confirmada.");
});

test('não cria a mensagem sem os campos obrigatórios', function () {
    $this->post(route('email-mensagens.store'), [])
        ->assertSessionHasErrors(['titulo', 'assunto', 'conteudo']);

    expect(EmailMensagem::count())->toBe(0);
});

test('recusa variáveis que o sistema não conhece', function () {
    $this->post(route('email-mensagens.store'), dadosDaMensagemDeEmail(['conteudo' => 'Olá {{nom}}, veja {{link_pagamento}}']))
        ->assertSessionHasErrors(['conteudo' => 'Variáveis desconhecidas: {{nom}}.']);

    expect(EmailMensagem::count())->toBe(0);
});

test('atualiza a mensagem', function () {
    $mensagem = EmailMensagem::factory()->create();

    $this->put(route('email-mensagens.update', $mensagem), dadosDaMensagemDeEmail([
        'titulo' => 'Novo título',
        'assunto' => 'Assunto novo',
        'conteudo' => 'Local: {{localidade}}',
    ]))->assertRedirect(route('email-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem atualizada.');

    expect($mensagem->refresh())
        ->titulo->toBe('Novo título')
        ->assunto->toBe('Assunto novo')
        ->conteudo->toBe('Local: {{localidade}}');
});

test('não atualiza a mensagem com dados inválidos', function () {
    $mensagem = EmailMensagem::factory()->create(['titulo' => 'Original']);

    $this->put(route('email-mensagens.update', $mensagem), dadosDaMensagemDeEmail(['titulo' => '']))
        ->assertSessionHasErrors('titulo');

    expect($mensagem->refresh()->titulo)->toBe('Original');
});

test('mostra o formulário de edição com os dados da mensagem', function () {
    $mensagem = EmailMensagem::factory()->create(['titulo' => 'Confirmação de inscrição']);

    $this->get(route('email-mensagens.edit', $mensagem))
        ->assertOk()
        ->assertSee('Confirmação de inscrição');
});

test('remove a mensagem', function () {
    $mensagem = EmailMensagem::factory()->create();

    $this->delete(route('email-mensagens.destroy', $mensagem))
        ->assertRedirect(route('email-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem removida.');

    $this->assertModelMissing($mensagem);
});

test('não remove a mensagem que uma automação usa', function () {
    $mensagem = EmailMensagem::factory()->create();
    Automacao::create([
        'evento_id' => Evento::factory()->create()->id,
        'email_mensagem_id' => $mensagem->id,
        'titulo' => 'Confirmação por e-mail',
        'canal' => 'email',
    ]);

    $this->delete(route('email-mensagens.destroy', $mensagem))
        ->assertRedirect(route('email-mensagens.index'))
        ->assertSessionHas('erro', 'Não é possível remover: a mensagem é usada em 1 automação(ões).');

    $this->assertModelExists($mensagem);
});

test('responde 404 para uma mensagem que não existe', function () {
    $this->get(route('email-mensagens.show', 999))->assertNotFound();
});

test('visitante não acessa as mensagens de e-mail', function () {
    auth()->logout();

    $this->get(route('email-mensagens.index'))->assertRedirect(route('login'));
});
