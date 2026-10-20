<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'minutos_disparo' => 'integer',
        ];
    }
}
