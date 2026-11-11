<?php

namespace App\Services;

use App\Models\Inscrito;
use Illuminate\Database\Eloquent\Builder;

/**
 * Escreve em CSV os inscritos de uma consulta, já com os filtros aplicados.
 */
class InscritoExportacaoService
{
    /**
     * Colunas do arquivo, na ordem em que aparecem.
     *
     * @var list<string>
     */
    private const CABECALHO = ['Nome', 'Email', 'Telefone', 'Evento'];

    /**
     * Grava o CSV no recurso informado (arquivo, stream de download...).
     *
     * Usa ";" como separador e BOM no início, que é o que o Excel em português espera.
     *
     * @param  Builder<Inscrito>  $inscritos
     * @param  resource  $saida
     */
    public function escrever(Builder $inscritos, $saida): void
    {
        // O BOM faz o Excel abrir o arquivo em UTF-8, senão "João" vira "JoÃ£o".
        fwrite($saida, "\xEF\xBB\xBF");

        fputcsv($saida, self::CABECALHO, ';', escape: '');

        // lazyByIdDesc lê aos poucos (500 por vez), para não carregar todos os inscritos na memória.
        foreach ($inscritos->lazyByIdDesc(500) as $inscrito) {
            fputcsv($saida, array_map($this->neutralizarFormula(...), [
                $inscrito->contato->nome,
                $inscrito->contato->email,
                $this->formatarTelefone($inscrito->contato->telefone),
                $inscrito->evento->titulo,
            ]), ';', escape: '');
        }
    }

    /**
     * Deixa o telefone só com números e com o código do Brasil na frente: "(11) 99012-3145" => "5511990123145".
     *
     * Telefone que já tem o 55 ("+55 11 99012-3145") não ganha outro. Se o tamanho não for de um telefone
     * brasileiro, os números são mantidos como estão, porque não dá para adivinhar o que falta.
     */
    private function formatarTelefone(?string $telefone): string
    {
        // Alguns telefones chegam codificados em URL pela Greatpages: "81%2099999-9999" é "81 99999-9999".
        $numeros = preg_replace('/\D/', '', rawurldecode((string) $telefone));

        // DDD + número tem 10 ou 11 dígitos. Com o 55 na frente, 12 ou 13.
        if (in_array(strlen($numeros), [10, 11], true)) {
            return '55'.$numeros;
        }

        return $numeros;
    }

    /**
     * Impede que o Excel execute como fórmula um texto digitado por terceiros (ex.: nome "=HYPERLINK(...)").
     *
     * Texto que começa com = + - @ ganha um apóstrofo na frente. Telefones como "+55 11 99012-3145" ficam como estão.
     */
    private function neutralizarFormula(?string $valor): string
    {
        $valor = (string) $valor;

        $pareceFormula = preg_match('/^[=+\-@\t\r]/', $valor) === 1;
        $pareceTelefone = preg_match('/^[+\-]?[\d\s().\-]+$/', $valor) === 1;

        return $pareceFormula && ! $pareceTelefone ? "'".$valor : $valor;
    }
}
