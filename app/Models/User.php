<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $attributes = [
        'role' => 'user',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isSeller(): bool
    {
        return $this->role === UserRole::Seller;
    }

    public function isBuyer(): bool
    {
        return $this->role === UserRole::User;
    }

    public function sellerProfile(): HasOne
    {
        return $this->hasOne(SellerProfile::class);
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(SellerPaymentMethod::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function ordersAsBuyer(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function ordersAsSeller(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class, 'advertiser_id');
    }

    public function feeSettlements(): HasMany
    {
        return $this->hasMany(SellerFeeSettlement::class, 'seller_id');
    }

    public function unsettledAdminFeeTotal(): int
    {
        return (int) $this->ordersAsSeller()
            ->where('status', OrderStatus::Completed)
            ->whereNull('fee_settlement_id')
            ->where('admin_fee', '>', 0)
            ->sum('admin_fee');
    }

    public function isOverUnpaidFeeLimit(): bool
    {
        $limit = PlatformSetting::current()->max_unpaid_admin_fee;

        return $limit > 0 && $this->unsettledAdminFeeTotal() >= $limit;
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            UserRole::Admin => route('admin.dashboard'),
            UserRole::Seller => route('seller.dashboard'),
            default => route('account.orders'),
        };
    }
}
