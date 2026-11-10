<?php

namespace App\Enums;

enum StatusPresenca: string
{
    case Pendente = 'pendente';
    case Presente = 'presente';
    case Ausente = 'ausente';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pendente => 'Pendente',
            self::Presente => 'Presente',
            self::Ausente => 'Ausente',
        };
    }

    /**
     * Classe CSS do badge de status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pendente => 'bs-amber',
            self::Presente => 'bs-green',
            self::Ausente => 'bs-slate',
        };
    }
}
