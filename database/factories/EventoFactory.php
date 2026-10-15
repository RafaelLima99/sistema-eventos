<?php

namespace Database\Factories;

use App\Enums\StatusEvento;
use App\Enums\Uf;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evento>
 */
class EventoFactory extends Factory
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
            'local' => fake()->company(),
            'preco_ingresso' => fake()->randomFloat(2, 0, 1000),
            'endereco' => fake()->streetAddress(),
            'cidade' => fake()->city(),
            'uf' => fake()->randomElement(Uf::cases()),
            'status' => StatusEvento::Rascunho,
            'data_evento' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'hora_inicio' => '09:00',
            'hora_fim' => '18:00',
            'link_inscricao' => fake()->url(),
            'link_pagamento' => fake()->url(),
        ];
    }

    /**
     * Indicate that the event is published.
     */
    public function publicado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusEvento::Publicado,
        ]);
    }
}
