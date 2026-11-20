<?php

namespace App\Enums;

enum AdPaymentStatus: string
{
    case Unpaid = 'unpaid';
    case PendingVerification = 'pending_verification';
    case Paid = 'paid';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Belum Dibayar',
            self::PendingVerification => 'Menunggu Verifikasi Bayar',
            self::Paid => 'Lunas',
            self::Rejected => 'Pembayaran Ditolak',
        };
    }
}
