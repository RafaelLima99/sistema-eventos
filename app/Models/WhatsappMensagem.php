<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('whatsapp_mensagens')]
#[Fillable(['titulo', 'nome_template_meta', 'variaveis_template'])]
class WhatsappMensagem extends Model
{
    /**
     * @return HasMany<Automacao, $this>
     */
    public function automacoes(): HasMany
    {
        return $this->hasMany(Automacao::class);
    }

    /**
     * @return HasMany<Disparo, $this>
     */
    public function disparos(): HasMany
    {
        return $this->hasMany(Disparo::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'variaveis_template' => 'array',
        ];
    }
}
