<?php

namespace App\Enums;

enum StatusAutomacao: string
{
    case Ativo = 'ativo';
    case Inativo = 'inativo';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Ativo => 'Ativo',
            self::Inativo => 'Inativo',
        };
    }

    /**
     * Classe CSS do badge de status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Ativo => 'bs-green',
            self::Inativo => 'bs-slate',
        };
    }
}
