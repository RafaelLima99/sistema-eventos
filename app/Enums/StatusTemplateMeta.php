<?php

namespace App\Enums;

enum StatusTemplateMeta: string
{
    case Aprovado = 'aprovado';
    case EmAnalise = 'em_analise';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Aprovado => 'Aprovado',
            self::EmAnalise => 'Em análise',
        };
    }

    /**
     * Classe CSS do badge de status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Aprovado => 'bs-green',
            self::EmAnalise => 'bs-amber',
        };
    }
}
