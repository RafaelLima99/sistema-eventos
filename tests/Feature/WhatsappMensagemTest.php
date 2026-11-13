<?php

use App\Models\Automacao;
use App\Models\Disparo;
use App\Models\Evento;
use App\Models\WhatsappMensagem;

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

test('lista as mensagens de WhatsApp com o status do template na Meta', function () {
    WhatsappMensagem::factory()->create(['titulo' => 'Lembrete de pagamento', 'nome_template_meta' => 'lembrete_pagamento_15min']);
    WhatsappMensagem::factory()->create(['titulo' => 'Convite quente', 'nome_template_meta' => 'convite_lista_quente']);

    $this->get(route('whatsapp-mensagens.index'))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertSee('Aprovado')
        ->assertSee('Convite quente')
        ->assertSee('Em análise');
});

test('busca mensagens pelo título', function () {
    WhatsappMensagem::factory()->create(['titulo' => 'Lembrete de pagamento']);
    WhatsappMensagem::factory()->create(['titulo' => 'Pós-evento']);

    $this->get(route('whatsapp-mensagens.index', ['busca_nome' => 'Lembrete']))
        ->assertOk()
        ->assertSee('Lembrete de pagamento')
        ->assertDontSee('Pós-evento');
});

test('pagina as mensagens de 15 em 15', function () {
    WhatsappMensagem::factory()->count(16)->create();

    $this->get(route('whatsapp-mensagens.index', ['page' => 2]))
        ->assertViewHas('whatsappMensagens', fn ($mensagens) => $mensagens->count() === 1);
});

test('mostra no formulário de criação só os templates aprovados', function () {
    $this->get(route('whatsapp-mensagens.create'))
        ->assertOk()
        ->assertSee('confirmacao_inscricao')
        ->assertDontSee('convite_lista_quente');
});

test('mostra os detalhes com os dados do template e o vínculo das variáveis', function () {
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

test('avisa nos detalhes quando o template não existe mais na Meta', function () {
    $mensagem = WhatsappMensagem::factory()->create(['nome_template_meta' => 'template_removido']);

    $this->get(route('whatsapp-mensagens.show', $mensagem))
        ->assertSee('template_removido')
        ->assertSee('não foi encontrado na Meta');
});

test('cria uma mensagem de WhatsApp com o template e o vínculo das variáveis', function () {
    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp())
        ->assertRedirect(route('whatsapp-mensagens.index'))
        ->assertSessionHas('sucesso', 'Mensagem criada.');

    expect(WhatsappMensagem::firstOrFail())
        ->titulo->toBe('Lembrete de pagamento · WhatsApp')
        ->nome_template_meta->toBe('lembrete_pagamento_15min')
        ->variaveis_template->toBe([1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'link_pagamento']);
});

test('não cria a mensagem sem título nem template', function () {
    $this->post(route('whatsapp-mensagens.store'), [])
        ->assertSessionHasErrors([
            'titulo',
            'nome_template_meta' => 'Escolha um template.',
        ]);

    expect(WhatsappMensagem::count())->toBe(0);
});

test('recusa um template que ainda está em análise na Meta', function () {
    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp(['nome_template_meta' => 'convite_lista_quente']))
        ->assertSessionHasErrors(['nome_template_meta' => 'Escolha um template aprovado.']);

    expect(WhatsappMensagem::count())->toBe(0);
});

test('recusa um template que não existe', function () {
    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp(['nome_template_meta' => 'inventado']))
        ->assertSessionHasErrors(['nome_template_meta' => 'Escolha um template aprovado.']);
});

test('salva a mensagem mesmo com variáveis sem campo escolhido', function () {
    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp([
        'variaveis_template' => [1 => 'nome_inscrito', 2 => '', 3 => 'link_pagamento'],
    ]))->assertRedirect(route('whatsapp-mensagens.index'));

    expect(WhatsappMensagem::firstOrFail()->variaveis_template)->toBe([1 => 'nome_inscrito', 2 => null, 3 => 'link_pagamento']);
});

test('recusa um campo do sistema que não existe', function () {
    $this->post(route('whatsapp-mensagens.store'), dadosDaMensagemDeWhatsapp([
        'variaveis_template' => [1 => 'nome_inscrito', 2 => 'campo_inventado', 3 => 'link_pagamento'],
    ]))->assertSessionHasErrors(['variaveis_template.2' => 'Escolha um campo do sistema válido.']);
});

test('atualiza a mensagem trocando o template e o vínculo', function () {
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
    $mensagem = WhatsappMensagem::factory()->create(['titulo' => 'Original']);

    $this->put(route('whatsapp-mensagens.update', $mensagem), dadosDaMensagemDeWhatsapp(['titulo' => '']))
        ->assertSessionHasErrors('titulo');

    expect($mensagem->refresh()->titulo)->toBe('Original');
});

test('mostra o formulário de edição com os dados da mensagem', function () {
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

test('responde 404 para uma mensagem que não existe', function () {
    $this->get(route('whatsapp-mensagens.show', 999))->assertNotFound();
});

test('visitante não acessa as mensagens de WhatsApp', function () {
    auth()->logout();

    $this->get(route('whatsapp-mensagens.index'))->assertRedirect(route('login'));
});
