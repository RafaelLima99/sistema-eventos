<?php

namespace Database\Factories;

use App\Models\Contato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contato>
 */
class ContatoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => fake()->numerify('(##) 9####-####'),
            'cpf' => fake()->unique()->numerify('###########'),
            'cargo' => fake()->jobTitle(),
            'negocio' => fake()->company(),
        ];
    }
}
