<?php

namespace App\Enums;

enum AdStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Paused = 'paused';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Review',
            self::Active => 'Aktif',
            self::Paused => 'Dijeda',
            self::Rejected => 'Ditolak',
        };
    }
}
