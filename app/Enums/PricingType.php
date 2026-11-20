<?php

namespace App\Enums;

enum PricingType: string
{
    case Free = 'free';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Gratis',
            self::Paid => 'Berbayar',
        };
    }
}
