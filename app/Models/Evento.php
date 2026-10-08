<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'titulo',
    'local',
    'preco_ingresso',
    'endereco',
    'cidade',
    'uf',
    'status',
    'data_evento',
    'hora_inicio',
    'hora_fim',
    'link_inscricao',
    'link_pagamento',
])]
class Evento extends Model
{
    use HasUuids;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * @return HasMany<Inscrito, $this>
     */
    public function inscritos(): HasMany
    {
        return $this->hasMany(Inscrito::class);
    }

    /**
     * @return BelongsToMany<Contato, $this>
     */
    public function contatos(): BelongsToMany
    {
        return $this->belongsToMany(Contato::class, 'inscritos')->withTimestamps();
    }

    /**
     * @return HasMany<Automacao, $this>
     */
    public function automacoes(): HasMany
    {
        return $this->hasMany(Automacao::class);
    }

    /**
     * @return BelongsToMany<Disparo, $this>
     */
    public function disparos(): BelongsToMany
    {
        return $this->belongsToMany(Disparo::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preco_ingresso' => 'decimal:2',
            'data_evento' => 'date',
        ];
    }
}
