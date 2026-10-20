<?php

use App\Services\CsvService;

/**
 * Salva o conteúdo num arquivo temporário e lê com o CsvService.
 *
 * @return array<int, array<string, string|null>>
 */
function lerCsv(string $conteudo): array
{
    $caminho = tempnam(sys_get_temp_dir(), 'csv');
    file_put_contents($caminho, $conteudo);

    try {
        return (new CsvService)->ler($caminho);
    } finally {
        unlink($caminho);
    }
}

test('lê as linhas usando o cabeçalho como chave', function () {
    expect(lerCsv("nome,cargo\nAna,CEO\nCarlos,CMO\n"))->toBe([
        2 => ['nome' => 'Ana', 'cargo' => 'CEO'],
        3 => ['nome' => 'Carlos', 'cargo' => 'CMO'],
    ]);
});

test('lê CSV do Excel salvo em UTF-8, com BOM e ponto e vírgula', function () {
    expect(lerCsv("\xEF\xBB\xBFNome;Negócio\nJoão;Educação\n"))->toBe([
        2 => ['nome' => 'João', 'negocio' => 'Educação'],
    ]);
});

test('lê CSV do Excel salvo em Windows-1252', function () {
    $conteudo = mb_convert_encoding("Nome;Negócio\nJoão;Educação\n", 'Windows-1252', 'UTF-8');

    expect(lerCsv($conteudo))->toBe([
        2 => ['nome' => 'João', 'negocio' => 'Educação'],
    ]);
});

test('pula linhas vazias e mantém o número original das linhas', function () {
    expect(lerCsv("nome,cargo\nAna,CEO\n,\n\nCarlos,\n"))->toBe([
        2 => ['nome' => 'Ana', 'cargo' => 'CEO'],
        5 => ['nome' => 'Carlos', 'cargo' => null],
    ]);
});
