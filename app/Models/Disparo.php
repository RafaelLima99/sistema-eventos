<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'whatsapp_mensagem_id',
    'todos_contatos',
    'filtro',
    'agendado_para',
    'status',
])]
class Disparo extends Model
{
    /**
     * @return BelongsTo<WhatsappMensagem, $this>
     */
    public function whatsappMensagem(): BelongsTo
    {
        return $this->belongsTo(WhatsappMensagem::class);
    }

    /**
     * @return BelongsToMany<Evento, $this>
     */
    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'todos_contatos' => 'boolean',
            'agendado_para' => 'datetime',
        ];
    }
}
