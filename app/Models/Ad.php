<?php

namespace App\Models;

use App\Enums\AdPaymentStatus;
use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'advertiser_id',
    'advertiser_name',
    'advertiser_email',
    'title',
    'description',
    'cta_text',
    'target_url',
    'banner_path',
    'placement',
    'slot_price',
    'admin_fee',
    'total_price',
    'payment_status',
    'payment_proof_path',
    'paid_at',
    'verified_at',
    'verified_by',
    'status',
    'admin_note',
    'starts_at',
    'ends_at',
    'impressions_count',
    'clicks_count',
])]
class Ad extends Model
{
    protected function casts(): array
    {
        return [
            'placement' => AdPlacement::class,
            'payment_status' => AdPaymentStatus::class,
            'status' => AdStatus::class,
            'slot_price' => 'integer',
            'admin_fee' => 'integer',
            'total_price' => 'integer',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'impressions_count' => 'integer',
            'clicks_count' => 'integer',
        ];
    }

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeActiveForPlacement(Builder $query, AdPlacement $placement): Builder
    {
        $now = now();

        return $query
            ->where('placement', $placement)
            ->where('status', AdStatus::Active)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now));
    }
}
