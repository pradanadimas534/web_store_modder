<?php

namespace App\Models;

use App\Enums\AdPlacement;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'order_admin_fee_percent',
    'order_admin_fee_fixed',
    'max_unpaid_admin_fee',
    'ad_price_catalog_banner',
    'ad_price_sidebar_promo',
    'ad_price_footer_strip',
    'ad_admin_fee_fixed',
    'admin_payment_provider',
    'admin_payment_account_number',
    'admin_payment_account_holder',
    'admin_payment_instructions',
])]
class PlatformSetting extends Model
{
    protected function casts(): array
    {
        return [
            'order_admin_fee_percent' => 'float',
            'order_admin_fee_fixed' => 'integer',
            'max_unpaid_admin_fee' => 'integer',
            'ad_price_catalog_banner' => 'integer',
            'ad_price_sidebar_promo' => 'integer',
            'ad_price_footer_strip' => 'integer',
            'ad_admin_fee_fixed' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->first() ?? static::create([
            'order_admin_fee_percent' => 5.00,
            'order_admin_fee_fixed' => 2000,
            'max_unpaid_admin_fee' => 50000,
            'ad_price_catalog_banner' => 50000,
            'ad_price_sidebar_promo' => 35000,
            'ad_price_footer_strip' => 25000,
            'ad_admin_fee_fixed' => 5000,
            'admin_payment_provider' => 'BCA',
            'admin_payment_account_number' => '0011223344',
            'admin_payment_account_holder' => 'Admin ModGame.id',
            'admin_payment_instructions' => 'Transfer sesuai total tagihan iklan dan unggah bukti pembayaran agar iklan segera diverifikasi.',
        ]);
    }

    public function calculateOrderAdminFee(int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $percentFee = (int) round($subtotal * ($this->order_admin_fee_percent / 100));

        return $percentFee + $this->order_admin_fee_fixed;
    }

    public function adSlotPrice(AdPlacement $placement): int
    {
        return match ($placement) {
            AdPlacement::CatalogBanner => $this->ad_price_catalog_banner,
            AdPlacement::SidebarPromo => $this->ad_price_sidebar_promo,
            AdPlacement::FooterStrip => $this->ad_price_footer_strip,
        };
    }

    public function calculateAdTotal(AdPlacement $placement): array
    {
        $slotPrice = $this->adSlotPrice($placement);
        $adminFee = $this->ad_admin_fee_fixed;

        return [
            'slot_price' => $slotPrice,
            'admin_fee' => $adminFee,
            'total_price' => $slotPrice + $adminFee,
        ];
    }
}
