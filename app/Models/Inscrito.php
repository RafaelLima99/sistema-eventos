<?php

namespace App\Models;

use App\Enums\StatusIngresso;
use App\Enums\StatusPresenca;
use Database\Factories\InscritoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    /** @use HasFactory<InscritoFactory> */
    use HasFactory;

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
            'status_ingresso' => StatusIngresso::class,
            'status_presenca' => StatusPresenca::class,
            'quantidade_ingresso' => 'integer',
            'valor_pago' => 'decimal:2',
        ];
    }
}
