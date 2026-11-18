<?php

use App\Models\Automacao;
use App\Models\Disparo;
use App\Models\Evento;
use App\Models\WhatsappMensagem;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de mensagem de WhatsApp (o template tem 3 variáveis).
 *
 * @return array<string, mixed>
 */
function dadosDaMensagemDeWhatsapp(array $sobrescrever = []): array
{
    return [
        'titulo' => 'Lembrete de pagamento · WhatsApp',
        'nome_template_meta' => 'lembrete_pagamento_15min',
        'variaveis_template' => [1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'link_pagamento'],
        ...$sobrescrever,
    ];
}

/**
 * Finge a resposta da API da Meta com os templates aprovados abaixo, no mesmo formato que a Meta devolve.
 */
function fingirTemplatesDaMeta(): void
{
    $template = fn (string $nome, string $texto) => [
        'id' => '1180239776'.strlen($nome),
        'name' => $nome,
        'language' => 'pt_BR',
        'category' => 'UTILITY',
        'quality_score' => ['score' => 'GREEN'],
        'components' => [['type' => 'BODY', 'text' => $texto]],
    ];

    Http::fake([
        'graph.facebook.com/v24.0/123456/message_templates*' => Http::response(['data' => [
            $template('confirmacao_inscricao', 'Olá {{1}}! Sua inscrição no {{2}} foi confirmada.'),
            $template('lembrete_pagamento_15min', 'Olá {{1}}! Sua vaga no {{2}} está reservada. Finalize: {{3}}'),
            $template('aviso_dia_evento', 'Bom dia, {{1}}! Hoje tem {{2}}. Início às {{3}}.'),
        ]]),
    ]);
}

test('lista as mensagens de WhatsApp mostrando se o template está aprovado', function () {
    fingirTemplatesDaMeta();

    WhatsappMensagem::factory()->create(['titulo' => 'Lembrete de pagamento', 'nome_template_meta' => 'lembrete_pagamento_15min']);
    WhatsappMensagem::factory()->create(['titulo' => 'Convite quente', 'nome_template_meta' => 'template_pausado']);

    $this->get(route('whatsapp-mensagens.index'))
        ->assertOk()
        ->assertSeeInOrder(['Lembrete de pagamento', 'Aprovado'])
        ->assertSeeInOrder(['Convite quente', 'Indisponível']);
});

test('busca mensagens pelo título', function () {
    fingirTemplatesDaMeta();

    WhatsappMensagem::factory()->create(['titulo' => 'Lembrete de pagamento']);
    WhatsappMensagem::factory()->create(['titulo' => 'Pós-evento']);

    $this->get(route('whatsapp-mensagens.index', ['busca_nome' => 'Lembrete']))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertDontSee('Pós-evento');
});

test('pagina as mensagens de 15 em 15', function () {
    fingirTemplatesDaMeta();

    WhatsappMensagem::factory()->count(16)->create();

    $this->get(route('whatsapp-mensagens.index', ['page' => 2]))
        ->assertViewHas('whatsappMensagens', fn ($mensagens) => $mensagens->count() === 1);
});

test('mostra no formulário de criação os templates aprovados', function () {
    fingirTemplatesDaMeta();

    $this->get(route('whatsapp-mensagens.create'))
        ->assertOk()
        ->assertSee('confirmacao_inscricao')
        ->assertSee('aviso_dia_evento');
});

test('mostra os detalhes com os dados do template e o vínculo das variáveis', function () {
    fingirTemplatesDaMeta();

    $mensagem = WhatsappMensagem::factory()->create([
        'titulo' => 'Lembrete de pagamento',
        'nome_template_meta' => 'lembrete_pagamento_15min',
        'variaveis_template' => [1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'link_pagamento'],
    ]);

    $this->get(route('whatsapp-mensagens.show', $mensagem))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertSee('lembrete_pagamento_15min')
        ->assertSee('Sua vaga no')
        ->assertSee('Nome do inscrito')
        ->assertSee('Nome do evento')
        ->assertSee('Link de pagamento');
});

test('avisa nos detalhes quando o template não está mais aprovado na Meta', function () {
    fingirTemplatesDaMeta();

    $mensagem = WhatsappMensagem::factory()->create(['nome_template_meta' => 'template_removido']);

    $this->get(route('whatsapp-mensagens.show', $mensagem))
        ->assertSee('template_removido')
        ->assertSee('Template indisponível')
        ->assertSee('não está aprovado na Meta');
});

test('cria uma mensagem de WhatsApp com o template e o vínculo das variáveis', function () {
    fingirTemplatesDaMeta();

    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp())
        ->assertRedirect(route('whatsapp-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem criada.');

    expect(WhatsappMensagem::firstOrFail())
        ->titulo->toBe('Lembrete de pagamento · WhatsApp')
        ->nome_template_meta->toBe('lembrete_pagamento_15min')
        ->variaveis_template->toBe([1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'link_pagamento']);
});

test('não cria a mensagem sem título nem template', function () {
    fingirTemplatesDaMeta();

    $this->post(route('whatsapp-mensagens.store'), [])
        ->assertSessionHasErrors([
            'titulo',
            'nome_template_meta' => 'Escolha um template.',
        ]);

    expect(WhatsappMensagem::count())->toBe(0);
});

test('recusa um template que não está aprovado na Meta', function () {
    fingirTemplatesDaMeta();

    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp(['nome_template_meta' => 'inventado']))
        ->assertSessionHasErrors(['nome_template_meta' => 'Escolha um template aprovado.']);
});

test('salva a mensagem mesmo com variáveis sem campo escolhido', function () {
    fingirTemplatesDaMeta();

    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp([
        'variaveis_template' => [1 => 'nome_inscrito', 2 => '', 3 => 'link_pagamento'],
    ]))->assertRedirect(route('whatsapp-mensagens.index'));

    expect(WhatsappMensagem::firstOrFail()->variaveis_template)->toBe([1 => 'nome_inscrito', 2 => null, 3 => 'link_pagamento']);
});

test('recusa um campo do sistema que não existe', function () {
    fingirTemplatesDaMeta();

    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp([
        'variaveis_template' => [1 => 'nome_inscrito', 2 => 'campo_inventado', 3 => 'link_pagamento'],
    ]))->assertSessionHasErrors(['variaveis_template.2' => 'Escolha um campo do sistema válido.']);
});

test('atualiza a mensagem trocando o template e o vínculo', function () {
    fingirTemplatesDaMeta();

    $mensagem = WhatsappMensagem::factory()->create();

    $this->put(route('whatsapp-mensagens.update', $mensagem), dadosDaMensagemDeWhatsapp([
        'titulo' => 'Aviso do dia',
        'nome_template_meta' => 'aviso_dia_evento',
        'variaveis_template' => [1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'horario'],
    ]))->assertRedirect(route('whatsapp-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem atualizada.');

    expect($mensagem->refresh())
        ->titulo->toBe('Aviso do dia')
        ->nome_template_meta->toBe('aviso_dia_evento')
        ->variaveis_template->toBe([1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'horario']);
});

test('não atualiza a mensagem com dados inválidos', function () {
    fingirTemplatesDaMeta();

    $mensagem = WhatsappMensagem::factory()->create(['titulo' => 'Original']);

    $this->put(route('whatsapp-mensagens.update', $mensagem), dadosDaMensagemDeWhatsapp(['titulo' => '']))
        ->assertSessionHasErrors('titulo');

    expect($mensagem->refresh()->titulo)->toBe('Original');
});

test('mostra o formulário de edição com os dados da mensagem', function () {
    fingirTemplatesDaMeta();

    $mensagem = WhatsappMensagem::factory()->create(['titulo' => 'Confirmação de inscrição']);

    $this->get(route('whatsapp-mensagens.edit', $mensagem))
        ->assertOk()
        ->assertSee('Confirmação de inscrição');
});

test('remove a mensagem', function () {
    $mensagem = WhatsappMensagem::factory()->create();

    $this->delete(route('whatsapp-mensagens.destroy', $mensagem))
        ->assertRedirect(route('whatsapp-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem removida.');

    $this->assertModelMissing($mensagem);
});

test('não remove a mensagem que uma automação ou um disparo usa', function (string $uso) {
    $mensagem = WhatsappMensagem::factory()->create();

    if ($uso === 'automação') {
        Automacao::create([
            'evento_id' => Evento::factory()->create()->id,
            'whatsapp_mensagem_id' => $mensagem->id,
            'titulo' => 'Confirmação por WhatsApp',
            'canal' => 'whatsapp',
        ]);
    } else {
        Disparo::create(['whatsapp_mensagem_id' => $mensagem->id]);
    }

    $this->delete(route('whatsapp-mensagens.destroy', $mensagem))
        ->assertRedirect(route('whatsapp-mensagens.index'))
        ->assertSessionHas('erro');

    $this->assertModelExists($mensagem);
})->with(['automação', 'disparo']);

test('busca na API da Meta só os templates aprovados, com o token configurado', function () {
    fingirTemplatesDaMeta();

    $this->get(route('whatsapp-mensagens.create'))->assertOk();

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer token-de-teste')
        && str_starts_with($request->url(), 'https://graph.facebook.com/v24.0/123456/message_templates')
        && $request['status'] === 'APPROVED');
});

test('busca as próximas páginas de templates da Meta', function () {
    Http::fake([
        'graph.facebook.com/v24.0/123456/message_templates*' => Http::response([
            'data' => [['id' => '1', 'name' => 'primeira_pagina', 'language' => 'pt_BR', 'category' => 'UTILITY', 'components' => [['type' => 'BODY', 'text' => 'Olá {{1}}']]]],
            'paging' => ['next' => 'https://graph.facebook.com/v24.0/pagina-2'],
        ]),
        'graph.facebook.com/v24.0/pagina-2' => Http::response([
            'data' => [['id' => '2', 'name' => 'segunda_pagina', 'language' => 'pt_BR', 'category' => 'UTILITY', 'components' => [['type' => 'BODY', 'text' => 'Oi {{1}}']]]],
        ]),
    ]);

    $this->get(route('whatsapp-mensagens.create'))
        ->assertSee('primeira_pagina')
        ->assertSee('segunda_pagina');
});

test('abre o formulário com um aviso quando a Meta não responde', function () {
    Http::fake(['graph.facebook.com/*' => Http::response(['error' => ['message' => 'Invalid OAuth access token']], 401)]);

    $this->get(route('whatsapp-mensagens.create'))
        ->assertOk()
        ->assertSee('Nenhum template aprovado encontrado na Meta.');
});

test('responde 404 para uma mensagem que não existe', function () {
    $this->get(route('whatsapp-mensagens.show', 999))->assertNotFound();
});

test('visitante não acessa as mensagens de WhatsApp', function () {
    auth()->logout();

    $this->get(route('whatsapp-mensagens.index'))->assertRedirect(route('login'));
});
