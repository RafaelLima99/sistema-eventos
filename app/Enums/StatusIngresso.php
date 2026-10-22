<?php

namespace App\Enums;

enum StatusIngresso: string
{
    case Pendente = 'pendente';
    case Pago = 'pago';
    case Cortesia = 'cortesia';
    case Gratuito = 'gratuito';
}
