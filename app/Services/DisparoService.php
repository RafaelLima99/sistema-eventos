<?php

namespace App\Services;

use Illuminate\Support\Carbon;

/**
 * Regras de como os disparos são guardados no banco.
 */
class DisparoService
{
    /**
     * Ajusta os dados do formulário para o formato do banco, antes de criar ou atualizar o disparo.
     *
     * @param  array<string, mixed>  $dados  dados já validados do formulário
     * @return array<string, mixed>
     */
    public function prepararDados(array $dados): array
    {
        $data = $dados['data'] ?? null;
        $hora = $dados['hora'] ?? null;

        return [
            ...$dados,

            // O formulário tem data e hora separadas; o banco guarda as duas juntas ("2026-12-10" + "10:00").
            // Sem data e hora, o envio é imediato e o campo fica vazio.
            'agendado_para' => $data && $hora ? Carbon::parse("$data $hora") : null,
        ];
    }
}
