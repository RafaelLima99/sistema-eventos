<?php

namespace App\Models;

use App\Enums\FiltroPublico;
use App\Enums\StatusDisparo;
use Database\Factories\DisparoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    /** @use HasFactory<DisparoFactory> */
    use HasFactory;

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
            'filtro' => FiltroPublico::class,
            'agendado_para' => 'datetime',
            'status' => StatusDisparo::class,
        ];
    }
}
