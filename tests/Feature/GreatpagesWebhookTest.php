<?php

use App\Enums\StatusIngresso;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Testing\TestResponse;

beforeEach(function () {
    config(['services.greatpages.token' => 'token-de-teste']);

    $this->evento = Evento::factory()->create();
});

/**
 * Formulário enviado pela Greatpages (form-urlencoded), com dados fictícios.
 *
 * @return array<string, string>
 */
function formularioGreatpages(array $sobrescrever = []): array
{
    return [
        'Digite_Seu_Nome' => 'Ana Silva',
        'Digite_Seu_Melhor_E_mail' => 'Ana.Silva@Example.com',
        'Digite_Seu_WhatsApp_com_DDD' => '81 99999-0000',
        'Digite_Seu_CPF' => '312.048.271-11',
        'Qual_o_seu_cargo' => 'Autônomo(a)',
        'Area_de_atuacao_do_seu_negocio' => 'Desenvolvimento de sistemas',
        'variacao_lp' => 'CE - Cor verde',
        'utm_source' => 'instagram',
        'utm_medium' => 'stories',
        'utm_campaign' => 'lancamento',
        'utm_content' => '',
        'platpag' => 'hotmart-ob',
        ...$sobrescrever,
    ];
}

/**
 * Envia o formulário para o link do evento, com o token no header Authorization (como a Greatpages faz).
 */
function enviarFormularioGreatpages(Evento $evento, array $dados, string $token = 'token-de-teste'): TestResponse
{
    return test()->post(route('webhooks.greatpages', $evento->uuid), $dados, ['Authorization' => $token]);
}

test('formulário cria o contato e a inscrição pendente no evento do link', function () {
    enviarFormularioGreatpages($this->evento, formularioGreatpages())->assertOk();

    $contato = Contato::firstOrFail();

    expect($contato)
        ->nome->toBe('Ana Silva')
        ->email->toBe('ana.silva@example.com')
        ->cpf->toBe('31204827111')
        ->telefone->toBe('81 99999-0000')
        ->cargo->toBe('Autônomo(a)')
        ->negocio->toBe('Desenvolvimento de sistemas');

    expect(Inscrito::firstOrFail())
        ->evento_id->toBe($this->evento->id)
        ->contato_id->toBe($contato->id)
        ->status_ingresso->toBe(StatusIngresso::Pendente)
        ->plataforma->toBe('hotmart')
        ->tipo_ingresso->toBe('Social')
        ->quantidade_ingresso->toBeNull()
        ->valor_pago->toBeNull()
        ->utm_source->toBe('instagram')
        ->utm_campaign->toBe('lancamento')
        ->utm_content->toBeNull();
});

test('atualiza o contato que já existe com o mesmo CPF', function () {
    $contato = Contato::factory()->create(['cpf' => '31204827111', 'cargo' => 'Analista']);

    enviarFormularioGreatpages($this->evento, formularioGreatpages())->assertOk();

    expect(Contato::count())->toBe(1)
        ->and($contato->refresh()->cargo)->toBe('Autônomo(a)')
        ->and(Inscrito::firstOrFail()->contato_id)->toBe($contato->id);
});

test('não altera a inscrição de quem já está inscrito no evento', function () {
    $contato = Contato::factory()->create(['cpf' => '31204827111']);
    $inscrito = Inscrito::create([
        'evento_id' => $this->evento->id,
        'contato_id' => $contato->id,
        'status_ingresso' => StatusIngresso::Pago,
        'tipo_ingresso' => 'oferta01',
        'quantidade_ingresso' => 1,
        'valor_pago' => 35,
    ]);

    enviarFormularioGreatpages($this->evento, formularioGreatpages())->assertOk();

    expect(Inscrito::count())->toBe(1)
        ->and($inscrito->refresh()->status_ingresso)->toBe(StatusIngresso::Pago);
});

test('recusa a chamada com token errado', function () {
    enviarFormularioGreatpages($this->evento, formularioGreatpages(), 'token-errado')->assertForbidden();

    expect(Contato::count())->toBe(0);
});

test('recusa a chamada sem token com mensagem própria', function () {
    enviarFormularioGreatpages($this->evento, formularioGreatpages(), '')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Token não informado no header Authorization.']);

    expect(Contato::count())->toBe(0);
});

test('recusa formulário sem e-mail', function () {
    enviarFormularioGreatpages($this->evento, formularioGreatpages(['Digite_Seu_Melhor_E_mail' => '']))
        ->assertUnprocessable();

    expect(Contato::count())->toBe(0);
});

test('a página do evento mostra a URL do webhook da Greatpages sem o token', function () {
    criarUsuarioLogado();

    $this->get(route('eventos.show', $this->evento))
        ->assertOk()
        ->assertSee(url("/webhooks/greatpages/eventos/{$this->evento->uuid}"))
        ->assertDontSee('token-de-teste');
});
