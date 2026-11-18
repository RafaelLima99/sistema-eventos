<?php

namespace Database\Factories;

use App\Enums\FiltroPublico;
use App\Enums\StatusDisparo;
use App\Models\Disparo;
use App\Models\WhatsappMensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Disparo>
 */
class DisparoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'whatsapp_mensagem_id' => WhatsappMensagem::factory(),
            'todos_contatos' => true,
            'filtro' => FiltroPublico::SemFiltro,
            'agendado_para' => null,
            'status' => StatusDisparo::Agendado,
        ];
    }

    /**
     * Indicate that the dispatch is a draft (not scheduled yet).
     */
    public function rascunho(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusDisparo::Rascunho,
        ]);
    }
}
