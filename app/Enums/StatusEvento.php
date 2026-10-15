<?php

namespace App\Enums;

enum StatusEvento: string
{
    case Rascunho = 'rascunho';
    case Publicado = 'publicado';
    case Concluido = 'concluido';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Rascunho => 'Rascunho',
            self::Publicado => 'Publicado',
            self::Concluido => 'Concluído',
        };
    }

    /**
     * Classe CSS do badge de status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Rascunho => 'bs-slate',
            self::Publicado => 'bs-green',
            self::Concluido => 'bs-blue',
        };
    }
}
