<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Leitura de arquivos CSV, inclusive os salvos pelo Excel em português.
 *
 * Não conhece nenhuma regra de negócio: só transforma o arquivo em linhas.
 */
class CsvService
{
    /**
     * Lê o CSV e devolve as linhas indexadas pelo número da linha no arquivo,
     * com as colunas nomeadas pelo cabeçalho normalizado.
     *
     * Ex.: "Nome;Negócio\nAna;Agência" => [2 => ['nome' => 'Ana', 'negocio' => 'Agência']]
     *
     * @return array<int, array<string, string|null>>
     */
    public function ler(string $caminho): array
    {
        // O Excel coloca um BOM (caracteres invisíveis) no início do arquivo.
        // Precisa sair antes da conversão, senão vira texto estranho no cabeçalho.
        $conteudo = preg_replace('/^\xEF\xBB\xBF/', '', file_get_contents($caminho));

        $conteudo = $this->converterParaUtf8($conteudo);

        $separador = $this->detectarSeparador($conteudo);

        // fgetcsv lê de um stream; usamos um em memória com o conteúdo já convertido.
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $conteudo);
        rewind($stream);

        $cabecalho = null;
        $linhas = [];
        $numeroDaLinha = 0;

        while (($valores = fgetcsv($stream, separator: $separador, escape: '')) !== false) {
            $numeroDaLinha++;

            // A primeira linha é o cabeçalho.
            if ($cabecalho === null) {
                $cabecalho = array_map($this->normalizarColuna(...), $valores);

                continue;
            }

            $linha = $this->montarLinha($cabecalho, $valores);

            // Pula linhas totalmente vazias (comum no fim de arquivos do Excel).
            if (array_filter($linha) === []) {
                continue;
            }

            $linhas[$numeroDaLinha] = $linha;
        }

        fclose($stream);

        return $linhas;
    }

    /**
     * O Excel no Brasil salva em Windows-1252; sem converter, "João" vira "Jo�o".
     */
    private function converterParaUtf8(string $conteudo): string
    {
        if (mb_check_encoding($conteudo, 'UTF-8')) {
            return $conteudo;
        }

        return mb_convert_encoding($conteudo, 'UTF-8', 'Windows-1252');
    }

    /**
     * O Excel em português usa ";" (a vírgula é o separador decimal).
     * Escolhe o que aparece mais vezes no cabeçalho.
     */
    private function detectarSeparador(string $conteudo): string
    {
        $cabecalho = strtok($conteudo, "\n");

        return substr_count($cabecalho, ';') > substr_count($cabecalho, ',') ? ';' : ',';
    }

    /**
     * Deixa o nome da coluna sem acento, espaço nas pontas ou maiúscula: " Negócio " => "negocio".
     */
    private function normalizarColuna(?string $coluna): string
    {
        return Str::of((string) $coluna)->ascii()->lower()->trim()->value();
    }

    /**
     * Junta o cabeçalho com os valores da linha. Valores vazios viram null.
     *
     * @param  list<string>  $cabecalho
     * @param  list<string|null>  $valores
     * @return array<string, string|null>
     */
    private function montarLinha(array $cabecalho, array $valores): array
    {
        $linha = [];

        foreach ($cabecalho as $posicao => $coluna) {
            $linha[$coluna] = trim($valores[$posicao] ?? '') ?: null;
        }

        return $linha;
    }
}
