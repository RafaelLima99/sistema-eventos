<?php

namespace Database\Factories;

use App\Models\WhatsappMensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WhatsappMensagem>
 */
class WhatsappMensagemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'nome_template_meta' => 'lembrete_pagamento_15min',
            'variaveis_template' => [1 => 'nome_inscrito', 2 => 'nome_evento', 3 => 'link_pagamento'],
        ];
    }
}
