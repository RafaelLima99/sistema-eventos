<?php

use App\Enums\FiltroPublico;
use App\Enums\StatusDisparo;
use App\Models\Disparo;
use App\Models\Evento;
use App\Models\WhatsappMensagem;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Dados válidos do formulário de disparo, para todos os contatos.
 *
 * @return array<string, mixed>
 */
function dadosDoDisparo(array $sobrescrever = []): array
{
    return [
        'whatsapp_mensagem_id' => WhatsappMensagem::factory()->create()->id,
        'todos_contatos' => '1',
        'filtro' => 'sem_filtro',
        'data' => '2026-12-10',
        'hora' => '10:00',
        'status' => 'agendado',
        ...$sobrescrever,
    ];
}

test('lista os disparos com a mensagem, os contatos, o envio e o status', function () {
    Disparo::factory()->create([
        'whatsapp_mensagem_id' => WhatsappMensagem::factory()->create(['titulo' => 'Convite lote 2'])->id,
        'agendado_para' => '2026-12-10 10:00:00',
    ]);

    $this->get(route('disparos.index'))
        ->assertOk()
        ->assertSee('Convite lote 2')
        ->assertSee('Todos os contatos')
        ->assertSee('10/12/2026 10:00')
        ->assertSee('Agendado');
});

test('mostra quantos eventos o disparo atinge', function () {
    $disparo = Disparo::factory()->create(['todos_contatos' => false]);
    $disparo->eventos()->attach(Evento::factory()->count(2)->create());

    $this->get(route('disparos.index'))->assertSee('2 evento(s)');
});

test('busca disparos pelo nome da mensagem', function () {
    Disparo::factory()->create(['whatsapp_mensagem_id' => WhatsappMensagem::factory()->create(['titulo' => 'Convite lote 2'])->id]);
    Disparo::factory()->create(['whatsapp_mensagem_id' => WhatsappMensagem::factory()->create(['titulo' => 'Pesquisa pós-evento'])->id]);

    $this->get(route('disparos.index', ['busca_nome' => 'Convite']))
        ->assertSee('Convite lote 2')
        ->assertDontSee('Pesquisa pós-evento');
});

test('pagina os disparos de 15 em 15', function () {
    Disparo::factory()->count(16)->create();

    $this->get(route('disparos.index', ['page' => 2]))
        ->assertViewHas('disparos', fn ($disparos) => $disparos->count() === 1);
});

test('cria um disparo agendado para todos os contatos', function () {
    $dados = dadosDoDisparo();

    $this->post(route('disparos.store'), $dados)
        ->assertRedirect(route('disparos.index'))
        ->assertSessionHas('sucesso', 'Disparo salvo.');

    $disparo = Disparo::firstOrFail();

    expect($disparo)
        ->whatsapp_mensagem_id->toBe($dados['whatsapp_mensagem_id'])
        ->todos_contatos->toBeTrue()
        ->filtro->toBe(FiltroPublico::SemFiltro)
        ->status->toBe(StatusDisparo::Agendado)
        ->and($disparo->agendado_para->format('Y-m-d H:i'))->toBe('2026-12-10 10:00')
        ->and($disparo->eventos)->toBeEmpty();
});

test('cria um disparo para os inscritos dos eventos marcados', function () {
    $eventos = Evento::factory()->count(2)->create();

    $this->post(route('disparos.store'), dadosDoDisparo([
        'todos_contatos' => '0',
        'eventos' => $eventos->pluck('id')->all(),
    ]))->assertRedirect(route('disparos.index'));

    $disparo = Disparo::firstOrFail();

    expect($disparo->todos_contatos)->toBeFalse()
        ->and($disparo->eventos->pluck('id')->sort()->values()->all())->toBe($eventos->pluck('id')->sort()->values()->all());
});

test('salva como rascunho quando clica em "Salvar rascunho"', function () {
    $this->post(route('disparos.store'), dadosDoDisparo(['status' => 'rascunho']));

    expect(Disparo::firstOrFail()->status)->toBe(StatusDisparo::Rascunho);
});

test('aceita o disparo sem data e hora, para envio imediato', function () {
    $this->post(route('disparos.store'), dadosDoDisparo(['data' => '', 'hora' => '']))
        ->assertRedirect(route('disparos.index'));

    expect(Disparo::firstOrFail()->agendado_para)->toBeNull();
});

test('exige data e hora juntas', function (array $quando, string $campo, string $erro) {
    $this->post(route('disparos.store'), dadosDoDisparo($quando))
        ->assertSessionHasErrors([$campo => $erro]);

    expect(Disparo::count())->toBe(0);
})->with([
    'só a data' => [['data' => '2026-12-10', 'hora' => ''], 'hora', 'Informe a hora do envio.'],
    'só a hora' => [['data' => '', 'hora' => '10:00'], 'data', 'Informe a data do envio.'],
]);

test('mostra a data e a hora salvas em campos separados na edição', function () {
    $disparo = Disparo::factory()->create(['agendado_para' => '2026-12-10 10:00:00']);

    $this->get(route('disparos.edit', $disparo))
        ->assertSee('name="data" type="date" value="2026-12-10"', false)
        ->assertSee('name="hora" type="time" value="10:00"', false);
});

test('exige pelo menos um evento quando o disparo é para inscritos em evento', function () {
    $this->post(route('disparos.store'), dadosDoDisparo(['todos_contatos' => '0']))
        ->assertSessionHasErrors(['eventos' => 'Marque pelo menos um evento.']);

    expect(Disparo::count())->toBe(0);
});

test('não cria o disparo sem os campos obrigatórios', function () {
    $this->post(route('disparos.store'), [])
        ->assertSessionHasErrors([
            'whatsapp_mensagem_id' => 'Escolha a mensagem de WhatsApp.',
            'todos_contatos',
            'filtro',
            'status',
        ]);

    expect(Disparo::count())->toBe(0);
});

test('não deixa marcar o disparo como concluído pelo formulário', function () {
    $this->post(route('disparos.store'), dadosDoDisparo(['status' => 'concluido']))
        ->assertSessionHasErrors('status');

    expect(Disparo::count())->toBe(0);
});

test('mostra o formulário de criação com eventos e mensagens', function () {
    Evento::factory()->create(['titulo' => 'Summit 2026']);
    WhatsappMensagem::factory()->create(['titulo' => 'Convite lote 2']);

    $this->get(route('disparos.create'))
        ->assertOk()
        ->assertSee('Summit 2026')
        ->assertSee('Convite lote 2');
});

test('mostra o formulário de edição com os eventos marcados', function () {
    $evento = Evento::factory()->create(['titulo' => 'Summit 2026']);
    $disparo = Disparo::factory()->create(['todos_contatos' => false]);
    $disparo->eventos()->attach($evento);

    $this->get(route('disparos.edit', $disparo))
        ->assertOk()
        ->assertSee('value="'.$evento->id.'" checked', false);
});

test('ao trocar para todos os contatos, remove os eventos que estavam marcados', function () {
    $disparo = Disparo::factory()->create(['todos_contatos' => false]);
    $disparo->eventos()->attach(Evento::factory()->create());

    $this->put(route('disparos.update', $disparo), dadosDoDisparo(['todos_contatos' => '1']))
        ->assertRedirect(route('disparos.index'))
        ->assertSessionHas('sucesso', 'Disparo atualizado.');

    expect($disparo->refresh()->todos_contatos)->toBeTrue()
        ->and($disparo->eventos)->toBeEmpty();
});

test('remove o disparo', function () {
    $disparo = Disparo::factory()->create();

    $this->delete(route('disparos.destroy', $disparo))
        ->assertRedirect(route('disparos.index'))
        ->assertSessionHas('sucesso', 'Disparo removido.');

    $this->assertModelMissing($disparo);
});

test('responde 404 para um disparo que não existe', function () {
    $this->get(route('disparos.edit', 999))->assertNotFound();
});

test('visitante não acessa os disparos', function () {
    auth()->logout();

    $this->get(route('disparos.index'))->assertRedirect(route('login'));
});
