<?php

use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Baixa o CSV e devolve as linhas já separadas em colunas (sem o BOM), com o cabeçalho na primeira.
 *
 * @return list<list<string|null>>
 */
function linhasDoCsvExportado(array $filtros = []): array
{
    $conteudo = test()->get(route('inscritos.exportacao.index', $filtros))->streamedContent();

    return array_map(
        fn (string $linha) => str_getcsv($linha, ';', escape: ''),
        explode("\n", trim(substr($conteudo, 3))),
    );
}

test('baixa um CSV com nome, e-mail, telefone e evento dos inscritos', function () {
    Inscrito::factory()
        ->for(Evento::factory()->create(['titulo' => 'Summit 2026']))
        ->for(Contato::factory()->create([
            'nome' => 'Ana Silva',
            'email' => 'ana@example.com',
            'telefone' => '(11) 99012-3145',
        ]))
        ->create();

    $resposta = $this->get(route('inscritos.exportacao.index'));

    $resposta->assertDownload('inscritos-'.now()->format('Y-m-d').'.csv');

    expect(linhasDoCsvExportado())->toBe([
        ['Nome', 'Email', 'Telefone', 'Evento'],
        ['Ana Silva', 'ana@example.com', '5511990123145', 'Summit 2026'],
    ]);
});

test('começa o arquivo com BOM para o Excel abrir em UTF-8', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'João']))->create();

    $conteudo = $this->get(route('inscritos.exportacao.index'))->streamedContent();

    expect($conteudo)->toStartWith("\xEF\xBB\xBF")->toContain('João');
});

test('exporta só os inscritos da busca por nome', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $linhas = linhasDoCsvExportado(['busca_nome' => 'Ana']);

    expect($linhas)->toHaveCount(2)
        ->and($linhas[1][0])->toBe('Ana Silva');
});

test('exporta só os inscritos do evento filtrado', function () {
    $evento = Evento::factory()->create(['titulo' => 'Evento A']);
    Inscrito::factory()->for($evento)->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $linhas = linhasDoCsvExportado(['evento' => $evento->id]);

    expect($linhas)->toHaveCount(2)
        ->and($linhas[1][0])->toBe('Ana Silva');
});

test('exporta só os inscritos do status de ingresso filtrado', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->pago()->create();
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Carlos Souza']))->create();

    $linhas = linhasDoCsvExportado(['status_ingresso' => 'pago']);

    expect($linhas)->toHaveCount(2)
        ->and($linhas[1][0])->toBe('Ana Silva');
});

test('exporta todos os inscritos quando não há filtro', function () {
    Inscrito::factory()->count(3)->create();

    expect(linhasDoCsvExportado())->toHaveCount(4);
});

test('exporta só o cabeçalho quando a busca não encontra ninguém', function () {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => 'Ana Silva']))->create();

    expect(linhasDoCsvExportado(['busca_nome' => 'Zé']))->toBe([['Nome', 'Email', 'Telefone', 'Evento']]);
});

test('impede que um texto vire fórmula no Excel', function (string $nome, string $esperado) {
    Inscrito::factory()->for(Contato::factory()->create(['nome' => $nome]))->create();

    expect(linhasDoCsvExportado()[1][0])->toBe($esperado);
})->with([
    'fórmula com =' => ['=HYPERLINK("http://x")', '\'=HYPERLINK("http://x")'],
    'fórmula com @' => ['@SOMA(A1)', "'@SOMA(A1)"],
]);

test('exporta o telefone só com números e com o 55 na frente', function (string $telefone, string $esperado) {
    Inscrito::factory()->for(Contato::factory()->create(['telefone' => $telefone]))->create();

    expect(linhasDoCsvExportado()[1][2])->toBe($esperado);
})->with([
    'com máscara' => ['(11) 99012-3145', '5511990123145'],
    'só números' => ['81999990000', '5581999990000'],
    'fixo com 10 dígitos' => ['(11) 3012-3145', '551130123145'],
    'DDD 55 sem o código do país' => ['55 99999-9999', '5555999999999'],
    'já com o 55' => ['+55 (11) 99012-3145', '5511990123145'],
    'codificado em URL' => ['81%2099999-9999', '5581999999999'],
    'tamanho inválido' => ['12345', '12345'],
]);

test('exporta o telefone vazio quando o contato não tem telefone', function () {
    Inscrito::factory()->for(Contato::factory()->create(['telefone' => null]))->create();

    expect(linhasDoCsvExportado()[1][2])->toBe('');
});

test('redireciona para o login quem não está autenticado a exportar', function () {
    auth()->logout();

    $this->get(route('inscritos.exportacao.index'))->assertRedirect(route('login'));
});
