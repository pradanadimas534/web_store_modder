<?php

namespace App\Enums;

enum SellerSettlementStatus: string
{
    case PendingVerification = 'pending_verification';
    case Verified = 'verified';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PendingVerification => 'Menunggu Verifikasi Admin',
            self::Verified => 'Terverifikasi Lunas',
            self::Rejected => 'Ditolak',
        };
    }
}
