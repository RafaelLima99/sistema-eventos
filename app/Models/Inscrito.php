<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'evento_id',
    'contato_id',
    'status_ingresso',
    'status_presenca',
    'plataforma',
    'tipo_ingresso',
    'quantidade_ingresso',
    'valor_pago',
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_content',
])]
class Inscrito extends Model
{
    /**
     * @return BelongsTo<Evento, $this>
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * @return BelongsTo<Contato, $this>
     */
    public function contato(): BelongsTo
    {
        return $this->belongsTo(Contato::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantidade_ingresso' => 'integer',
            'valor_pago' => 'decimal:2',
        ];
    }
}
