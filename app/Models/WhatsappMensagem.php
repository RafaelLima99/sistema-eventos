<?php

namespace App\Models;

use Database\Factories\WhatsappMensagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('whatsapp_mensagens')]
#[Fillable(['titulo', 'nome_template_meta', 'variaveis_template'])]
class WhatsappMensagem extends Model
{
    /** @use HasFactory<WhatsappMensagemFactory> */
    use HasFactory;

    /**
     * Campos do sistema que podem preencher uma variável do template (chave salva no banco => nome exibido no frontend).
     *
     * @var array<string, string>
     */
    public const CAMPOS_DO_SISTEMA = [
        'nome_inscrito' => 'Nome do inscrito',
        'responsavel' => 'Responsável',
        'nome_evento' => 'Nome do evento',
        'data_evento' => 'Data do evento',
        'horario' => 'Horário',
        'local' => 'Local',
        'local_cidade' => 'Local + cidade',
        'link_inscricao' => 'Link de inscrição',
        'link_pagamento' => 'Link de pagamento',
    ];

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
