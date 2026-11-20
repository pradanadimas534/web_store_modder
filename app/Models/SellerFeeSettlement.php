<?php

namespace App\Models;

use App\Enums\SellerSettlementStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'settlement_number',
    'seller_id',
    'amount',
    'orders_count',
    'status',
    'payment_proof_path',
    'payment_note',
    'admin_note',
    'submitted_at',
    'verified_at',
    'verified_by',
])]
class SellerFeeSettlement extends Model
{
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'orders_count' => 'integer',
            'status' => SellerSettlementStatus::class,
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'fee_settlement_id');
    }

    public static function generateSettlementNumber(): string
    {
        return 'STL-'.now()->format('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }
}
