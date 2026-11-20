<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Active = 'active';
    case Rejected = 'rejected';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::PendingReview => 'Menunggu Review',
            self::Active => 'Aktif',
            self::Rejected => 'Ditolak',
            self::Disabled => 'Dinonaktifkan',
        };
    }
}
