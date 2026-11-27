<?php

namespace App\Models;

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use App\Enums\TipoGatilho;
use Database\Factories\AutomacaoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('automacoes')]
#[Fillable([
    'evento_id',
    'whatsapp_mensagem_id',
    'email_mensagem_id',
    'titulo',
    'canal',
    'tipo_gatilho',
    'minutos_disparo',
    'filtro',
    'status',
])]
class Automacao extends Model
{
    /** @use HasFactory<AutomacaoFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Evento, $this>
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * @return BelongsTo<WhatsappMensagem, $this>
     */
    public function whatsappMensagem(): BelongsTo
    {
        return $this->belongsTo(WhatsappMensagem::class);
    }

    /**
     * @return BelongsTo<EmailMensagem, $this>
     */
    public function emailMensagem(): BelongsTo
    {
        return $this->belongsTo(EmailMensagem::class);
    }

    /**
     * Traz só uma das duas mensagens: verifica se o canal é WhatsApp ou e-mail e devolve a mensagem desse canal.
     */
    public function mensagem(): WhatsappMensagem|EmailMensagem|null
    {
        return $this->canal === CanalMensagem::Whatsapp ? $this->whatsappMensagem : $this->emailMensagem;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'canal' => CanalMensagem::class,
            'tipo_gatilho' => TipoGatilho::class,
            'minutos_disparo' => 'integer',
            'filtro' => FiltroPublico::class,
            'status' => StatusAutomacao::class,
        ];
    }
}
