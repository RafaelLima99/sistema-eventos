<?php

namespace App\Services;

use App\Models\Contato;

/**
 * Regras para salvar contatos sem duplicar a mesma pessoa.
 * Usado pela importação de CSV e pelo webhook da Hotmart.
 */
class ContatoService
{
    /**
     * Atualiza o contato que já existe (mesmo CPF ou e-mail) ou cria um novo.
     *
     * Campos vazios não sobrescrevem dados já salvos.
     * Para saber se o contato foi criado agora, use $contato->wasRecentlyCreated.
     *
     * @param  array<string, string|null>  $dados
     */
    public function criarOuAtualizar(array $dados): Contato
    {
        $dados = array_filter($dados);

        $contato = $this->buscarPorCpfOuEmail($dados['cpf'] ?? null, $dados['email'] ?? null);

        if ($contato) {
            $contato->update($dados);

            return $contato;
        }

        return Contato::create($dados);
    }

    /**
     * Procura pelo CPF primeiro (uma pessoa tem um CPF só) e, se não achar, pelo e-mail.
     */
    private function buscarPorCpfOuEmail(?string $cpf, ?string $email): ?Contato
    {
        if (filled($cpf)) {
            $contato = Contato::where('cpf', $cpf)->first();

            if ($contato) {
                return $contato;
            }
        }

        if (filled($email)) {
            return Contato::where('email', $email)->first();
        }

        return null;
    }
}
