<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PricingType;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'seller_id',
    'game_id',
    'category_id',
    'name',
    'slug',
    'short_description',
    'description',
    'pricing_type',
    'price',
    'promo_price',
    'thumbnail_path',
    'mod_version',
    'compatible_game_version',
    'dependencies',
    'install_guide',
    'changelog',
    'file_path',
    'file_original_name',
    'file_size',
    'file_checksum',
    'status',
    'rejection_reason',
    'downloads_count',
    'is_featured',
])]
class Product extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'pricing_type' => PricingType::class,
            'status' => ProductStatus::class,
            'price' => 'integer',
            'promo_price' => 'integer',
            'file_size' => 'integer',
            'downloads_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Active);
    }

    public function isFree(): bool
    {
        return $this->pricing_type === PricingType::Free;
    }

    public function isPaid(): bool
    {
        return $this->pricing_type === PricingType::Paid;
    }

    public function isActive(): bool
    {
        return $this->status === ProductStatus::Active;
    }

    public function effectivePrice(): int
    {
        if ($this->isFree()) {
            return 0;
        }

        if ($this->promo_price !== null && $this->promo_price > 0 && $this->promo_price < $this->price) {
            return $this->promo_price;
        }

        return $this->price;
    }

    public function formattedFileSize(): string
    {
        $bytes = max(0, (int) $this->file_size);

        if ($bytes < 1024) {
            return $bytes.' B';
        }

        if ($bytes < 1048576) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return number_format($bytes / 1048576, 2).' MB';
    }

    public function hasBeenPurchasedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return OrderItem::query()
            ->where('product_id', $this->id)
            ->whereHas('order', fn (Builder $q) => $q
                ->where('buyer_id', $user->id)
                ->where('status', OrderStatus::Completed)
            )
            ->exists();
    }
}
