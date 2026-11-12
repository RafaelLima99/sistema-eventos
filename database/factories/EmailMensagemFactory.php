<?php

namespace Database\Factories;

use App\Models\EmailMensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EmailMensagem>
 */
class EmailMensagemFactory extends Factory
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
            'assunto' => 'Inscrição confirmada — {{evento}}',
            'conteudo' => "Olá {{nome}},\n\nSua inscrição no {{evento}} foi confirmada.",
        ];
    }
}
