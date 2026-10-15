<?php

namespace App\Models;

use Database\Factories\ContatoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
    /** @use HasFactory<ContatoFactory> */
    use HasFactory;

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

    public function iniciaisDoNome(): string
    {
        // Separa o nome em partes, ignorando espaços extras.
        $nomes = Str::of($this->nome)->squish()->explode(' ');

        // Primeira letra do primeiro e do último nome, em maiúsculas.
        return Str::upper(
            Str::substr($nomes->first(), 0, 1).($nomes->count() > 1 ? Str::substr($nomes->last(), 0, 1) : '')
        );
    }

    /**
     * Devolve o CPF com máscara: "31204827111" => "312.048.271-11".
     */
    public function cpfFormatado(): ?string
    {
        if (! $this->cpf) {
            return null;
        }

        return substr($this->cpf, 0, 3).'.'.substr($this->cpf, 3, 3).'.'.substr($this->cpf, 6, 3).'-'.substr($this->cpf, 9, 2);
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
