<?php

namespace Database\Factories;

use App\Enums\StatusIngresso;
use App\Enums\StatusPresenca;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inscrito>
 */
class InscritoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evento_id' => Evento::factory(),
            'contato_id' => Contato::factory(),
            'status_ingresso' => StatusIngresso::Pendente,
            'status_presenca' => StatusPresenca::Pendente,
            'plataforma' => fake()->randomElement(['hotmart', 'greatpages']),
            'tipo_ingresso' => fake()->randomElement(['VIP', 'Inteira', 'Meia']),
            'quantidade_ingresso' => 1,
            'valor_pago' => fake()->randomFloat(2, 0, 1000),
        ];
    }

    /**
     * Indicate that the ticket was paid.
     */
    public function pago(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_ingresso' => StatusIngresso::Pago,
        ]);
    }

    /**
     * Indicate that the person attended the event.
     */
    public function presente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_presenca' => StatusPresenca::Presente,
        ]);
    }

    /**
     * Indicate that the registration has no purchase data yet (e.g. came only from the Greatpages form).
     */
    public function semDadosDaCompra(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_ingresso' => null,
            'quantidade_ingresso' => null,
            'valor_pago' => null,
        ]);
    }
}
