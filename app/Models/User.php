<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Iniciais do primeiro e do último nome, ex.: "Rafael Lima" => "RL".
     */
    public function iniciaisDoNome(): string
    {
        // Separa o nome em partes, ignorando espaços extras.
        $nomes = Str::of($this->name)->squish()->explode(' ');

        // Primeira letra do primeiro e do último nome, em maiúsculas.
        return Str::upper(
            Str::substr($nomes->first(), 0, 1).($nomes->count() > 1 ? Str::substr($nomes->last(), 0, 1) : '')
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
