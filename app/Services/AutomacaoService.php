<?php

namespace App\Services;

use App\Enums\CanalMensagem;

/**
 * Regras de como as automações são guardadas no banco.
 */
class AutomacaoService
{
    /**
     * Ajusta os dados do formulário para o formato do banco, antes de criar ou atualizar a automação.
     *
     * @param  array<string, mixed>  $dados  dados já validados do formulário
     * @return array<string, mixed>
     */
    public function prepararDados(array $dados): array
    {
        $canalEscolhido = CanalMensagem::from($dados['canal']);

        return [
            ...$dados,

            // O formulário tem horas e minutos separados; o banco guarda o total em minutos (1h30 => 90).
            'minutos_disparo' => (int) $dados['horas'] * 60 + (int) $dados['minutos'],

            // Guarda só a mensagem do canal escolhido. O select do outro canal fica escondido na tela,
            // mas continua sendo enviado: sem isto, ao trocar de WhatsApp para e-mail, a mensagem de
            // WhatsApp antiga continuaria ligada à automação (e não daria para removê-la depois).
            'whatsapp_mensagem_id' => $canalEscolhido === CanalMensagem::Whatsapp ? ($dados['whatsapp_mensagem_id'] ?? null) : null,
            'email_mensagem_id' => $canalEscolhido === CanalMensagem::Email ? ($dados['email_mensagem_id'] ?? null) : null,
        ];
    }
}
