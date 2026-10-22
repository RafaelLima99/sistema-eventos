<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Importa contatos de um CSV. Contatos com CPF ou e-mail já cadastrados são atualizados.
 */
class ContatoImportacaoService
{
    /**
     * Nome da coluna no CSV => campo do contato.
     *
     * @var array<string, string>
     */
    private const COLUNAS = [
        'nome' => 'nome',
        'email' => 'email',
        'e-mail' => 'email',
        'whatsapp' => 'telefone',
        'telefone' => 'telefone',
        'cpf' => 'cpf',
        'cargo' => 'cargo',
        'negocio' => 'negocio',
    ];

    public function __construct(
        private CsvService $csvService,
        private ContatoService $contatoService,
    ) {}

    /**
     * @return array{criados: int, atualizados: int, ignoradas: list<int>}
     *
     * @throws InvalidArgumentException quando o arquivo não tem a coluna "nome".
     */
    public function importar(string $caminho): array
    {
        $linhas = $this->csvService->ler($caminho);

        if ($linhas !== [] && ! array_key_exists('nome', reset($linhas))) {
            throw new InvalidArgumentException('O arquivo precisa ter a coluna "nome".');
        }

        $resultado = ['criados' => 0, 'atualizados' => 0, 'ignoradas' => []];

        foreach ($linhas as $numeroDaLinha => $linha) {
            $dados = $this->montarDados($linha);

            if ($this->linhaInvalida($dados)) {
                $resultado['ignoradas'][] = $numeroDaLinha;

                continue;
            }

            $contato = $this->contatoService->criarOuAtualizar($dados);

            $contato->wasRecentlyCreated ? $resultado['criados']++ : $resultado['atualizados']++;
        }

        return $resultado;
    }

    /**
     * Converte a linha do CSV nos campos do contato, ignorando colunas desconhecidas.
     *
     * @param  array<string, string|null>  $linha
     * @return array<string, string|null>
     */
    private function montarDados(array $linha): array
    {
        $dados = [];

        foreach ($linha as $coluna => $valor) {
            if (isset(self::COLUNAS[$coluna])) {
                $dados[self::COLUNAS[$coluna]] = $valor;
            }
        }

        // CPF sem máscara: "312.048.271-11" => "31204827111".
        if (isset($dados['cpf'])) {
            $dados['cpf'] = preg_replace('/\D/', '', $dados['cpf']) ?: null;
        }

        // E-mail em minúsculas: "Ana@Empresa.com" => "ana@empresa.com".
        if (isset($dados['email'])) {
            $dados['email'] = Str::lower($dados['email']);
        }

        return $dados;
    }

    /**
     * Diz se a linha tem dados inválidos e deve ser ignorada.
     * Exige CPF ou e-mail, senão o contato não pode ser reconhecido numa próxima importação.
     * O CPF não é "unique" aqui porque, se já existir, o contato é atualizado.
     *
     * @param  array<string, string|null>  $dados
     */
    private function linhaInvalida(array $dados): bool
    {
        return Validator::make($dados, [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'required_without:cpf', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'digits:11'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'negocio' => ['nullable', 'string', 'max:255'],
        ])->fails();
    }
}
