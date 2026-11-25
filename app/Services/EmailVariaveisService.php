<?php

namespace App\Services;

use App\Models\Inscrito;

/**
 * Variáveis que podem ser escritas nas mensagens de e-mail, no formato {{nome}}.
 */
class EmailVariaveisService
{
    /**
     * Variáveis que o sistema sabe preencher.
     *
     * @var list<string>
     */
    private const DISPONIVEIS = [
        '{{nome}}',
        '{{evento}}',
        '{{data}}',
        '{{horario}}',
        '{{localidade}}',
        '{{link_inscricao}}',
        '{{link_pagamento}}',
        '{{responsavel}}',
        '{{tipo_ingresso}}',
    ];

    /**
     * @return list<string>
     */
    public function listarDisponiveis(): array
    {
        return self::DISPONIVEIS;
    }

    /**
     * Lê os textos e devolve as variáveis que aparecem neles, sem repetir.
     *
     * Ex.: "Olá {{nome}}", "Evento {{evento}}, {{ nome }}" => ['{{nome}}', '{{evento}}'].
     *
     * @return list<string>
     */
    public function extrairVariaveis(string ...$textos): array
    {
        // Acha cada {{palavra}}, com ou sem espaços dentro das chaves, e guarda só a palavra em $encontradas[1].
        preg_match_all('/\{\{\s*(\w+)\s*\}\}/', implode("\n", $textos), $encontradas);

        // Reescreve cada uma sem espaços, para "{{ nome }}" e "{{nome}}" contarem como a mesma.
        $variaveis = array_map(fn (string $nome) => '{{'.$nome.'}}', $encontradas[1]);

        return array_values(array_unique($variaveis));
    }

    /**
     * Confere se os textos só usam variáveis que o sistema conhece.
     * Uma variável errada (ex.: "{{nom}}") seria enviada ao contato do jeito que foi escrita.
     *
     * @return string|null mensagem de erro, ou null se estiver tudo certo
     */
    public function obterErroDasVariaveis(string ...$textos): ?string
    {
        $desconhecidas = array_diff($this->extrairVariaveis(...$textos), self::DISPONIVEIS);

        if ($desconhecidas === []) {
            return null;
        }

        return 'Variáveis desconhecidas: '.implode(', ', $desconhecidas).'.';
    }

    /**
     * Troca as variáveis do texto pelos dados do inscrito e do evento.
     *
     * Ex.: "Olá {{nome}}, te esperamos no {{evento}}" => "Olá Ana, te esperamos no Summit".
     */
    public function preencher(string $texto, Inscrito $inscrito): string
    {
        $evento = $inscrito->evento;

        // Valor de cada variável (sem as chaves).
        $valores = [
            'nome' => $inscrito->contato->nome,
            'evento' => $evento->titulo,
            'data' => $evento->data_evento->format('d/m/Y'),
            'horario' => $evento->hora_inicio,
            'localidade' => "{$evento->local} – {$evento->cidade}/{$evento->uf->value}",
            'link_inscricao' => $evento->link_inscricao,
            'link_pagamento' => $evento->link_pagamento,
            // Ainda não existe um campo de responsável no sistema: a variável sai vazia.
            'responsavel' => '',
            'tipo_ingresso' => $inscrito->tipo_ingresso,
        ];

        // Acha cada {{palavra}} (com ou sem espaços dentro das chaves) e troca pelo valor dela.
        // Variável sem valor (ex.: evento sem link de pagamento) sai vazia.
        return preg_replace_callback(
            '/\{\{\s*(\w+)\s*\}\}/',
            fn (array $encontrada) => (string) ($valores[$encontrada[1]] ?? ''),
            $texto,
        );
    }
}
