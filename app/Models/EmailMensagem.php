<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('email_mensagens')]
#[Fillable(['titulo', 'assunto', 'conteudo', 'variaveis'])]
class EmailMensagem extends Model
{
    /**
     * @return HasMany<Automacao, $this>
     */
    public function automacoes(): HasMany
    {
        return $this->hasMany(Automacao::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'variaveis' => 'array',
        ];
    }
}
