<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'order_number',
    'buyer_id',
    'seller_id',
    'seller_payment_method_id',
    'payment_method_snapshot',
    'subtotal',
    'admin_fee',
    'total',
    'seller_net_amount',
    'fee_settlement_id',
    'status',
    'payment_proof_path',
    'payment_note',
    'seller_note',
    'rejection_reason',
    'paid_at',
    'verified_at',
    'verified_by',
])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_method_snapshot' => 'array',
            'subtotal' => 'integer',
            'admin_fee' => 'integer',
            'total' => 'integer',
            'seller_net_amount' => 'integer',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(SellerPaymentMethod::class, 'seller_payment_method_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function feeSettlement(): BelongsTo
    {
        return $this->belongsTo(SellerFeeSettlement::class, 'fee_settlement_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    public function canUploadProof(): bool
    {
        return in_array($this->status, [OrderStatus::PendingPayment, OrderStatus::Rejected], true);
    }

    public static function generateOrderNumber(): string
    {
        return 'MG-'.now()->format('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }
}
