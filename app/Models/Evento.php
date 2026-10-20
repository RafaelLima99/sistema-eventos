<?php

namespace App\Models;

use App\Enums\StatusEvento;
use App\Enums\Uf;
use Database\Factories\EventoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    /** @use HasFactory<EventoFactory> */
    use HasFactory, HasUuids;

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
     * Devolve a hora sem os segundos: "09:00:00" => "09:00".
     *
     * @return Attribute<string, never>
     */
    protected function horaInicio(): Attribute
    {
        return Attribute::get(fn (string $hora): string => substr($hora, 0, 5));
    }

    /**
     * Devolve a hora sem os segundos: "18:00:00" => "18:00".
     *
     * @return Attribute<?string, never>
     */
    protected function horaFim(): Attribute
    {
        return Attribute::get(fn (?string $hora): ?string => $hora ? substr($hora, 0, 5) : null);
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
            'status' => StatusEvento::class,
            'uf' => Uf::class,
        ];
    }
}
