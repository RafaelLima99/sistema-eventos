<?php

namespace App\Enums;

enum StatusIngresso: string
{
    case Pendente = 'pendente';
    case Pago = 'pago';
    case Cortesia = 'cortesia';
    case Gratuito = 'gratuito';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pendente => 'Pendente',
            self::Pago => 'Pago',
            self::Cortesia => 'Cortesia',
            self::Gratuito => 'Gratuito',
        };
    }

    /**
     * Classe CSS do badge de status.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pendente => 'bs-amber',
            self::Pago => 'bs-green',
            self::Cortesia, self::Gratuito => 'bs-slate',
        };
    }
}
