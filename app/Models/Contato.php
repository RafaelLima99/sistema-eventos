<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'chatwoot_contato_id',
    'chatwoot_conversa_id',
    'nome',
    'email',
    'telefone',
    'cpf',
    'cargo',
    'negocio',
])]
class Contato extends Model
{
    /**
     * @return HasMany<Inscrito, $this>
     */
    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscrito::class);
    }

    /**
     * @return BelongsToMany<Evento, $this>
     */
    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'inscritos')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'chatwoot_contato_id' => 'integer',
            'chatwoot_conversa_id' => 'integer',
        ];
    }
}
