<?php

namespace App\Enums;

enum CanalMensagem: string
{
    case Whatsapp = 'whatsapp';
    case Email = 'email';

    /**
     * Nome exibido na interface.
     */
    public function label(): string
    {
        return match ($this) {
            self::Whatsapp => 'WhatsApp',
            self::Email => 'E-mail',
        };
    }

    /**
     * Ícone do Bootstrap Icons.
     */
    public function icone(): string
    {
        return match ($this) {
            self::Whatsapp => 'bi-whatsapp',
            self::Email => 'bi-envelope',
        };
    }
}
