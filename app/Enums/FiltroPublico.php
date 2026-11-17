<?php

namespace App\Enums;

enum FiltroPublico: string
{
    case SemFiltro = 'sem_filtro';
    case NaoPagantes = 'nao_pagantes';
    case Pagantes = 'pagantes';
    case Presentes = 'presentes';
    case Ausentes = 'ausentes';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::SemFiltro => 'Toda a base',
            self::NaoPagantes => 'Não pagantes',
            self::Pagantes => 'Pagantes',
            self::Presentes => 'Presentes',
            self::Ausentes => 'Ausentes',
        };
    }
}
