<?php

namespace App\Enums;

/**
 * A partir de quando o tempo da automação é contado.
 */
enum TipoGatilho: string
{
    case AposInscricao = 'apos_inscricao';
    case AposPagamento = 'apos_pagamento';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::AposInscricao => 'Após a inscrição',
            self::AposPagamento => 'Após o pagamento',
        };
    }
}
