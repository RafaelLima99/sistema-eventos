<?php

namespace App\Models;

use Database\Factories\EmailMensagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('email_mensagens')]
#[Fillable(['titulo', 'assunto', 'conteudo'])]
class EmailMensagem extends Model
{
    /** @use HasFactory<EmailMensagemFactory> */
    use HasFactory;

    /**
     * @return HasMany<Automacao, $this>
     */
    public function automacoes(): HasMany
    {
        return $this->hasMany(Automacao::class);
    }
}
