<?php

namespace App\Services;

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
        '{{link}}',
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
}
