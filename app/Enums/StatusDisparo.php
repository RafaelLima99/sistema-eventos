<?php

namespace App\Enums;

enum StatusDisparo: string
{
    case Rascunho = 'rascunho';
    case Agendado = 'agendado';
    case Concluido = 'concluido';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Rascunho => 'Rascunho',
            self::Agendado => 'Agendado',
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
            self::Agendado => 'bs-amber',
            self::Concluido => 'bs-blue',
        };
    }
}
