<?php

namespace Database\Factories;

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Models\Automacao;
use App\Models\EmailMensagem;
use App\Models\Evento;
use App\Models\WhatsappMensagem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Automacao>
 */
class AutomacaoFactory extends Factory
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
            'titulo' => 'Lembrete de pagamento',
            'canal' => CanalMensagem::Whatsapp,
            'whatsapp_mensagem_id' => WhatsappMensagem::factory(),
            'email_mensagem_id' => null,
            'minutos_disparo' => 15,
            'filtro' => FiltroPublico::NaoPagantes,
            'status' => StatusAutomacao::Ativo,
        ];
    }

    /**
     * Indicate that the automation sends an e-mail instead of a WhatsApp message.
     */
    public function email(): static
    {
        return $this->state(fn (array $attributes) => [
            'canal' => CanalMensagem::Email,
            'whatsapp_mensagem_id' => null,
            'email_mensagem_id' => EmailMensagem::factory(),
        ]);
    }

    /**
     * Indicate that the automation is turned off.
     */
    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusAutomacao::Inativo,
        ]);
    }
}
