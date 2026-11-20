<?php

namespace Tests\Unit;

use App\Enums\AdPlacement;
use App\Enums\PricingType;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductLogicTest extends TestCase
{
    public function test_effective_price_calculation(): void
    {
        $free = new Product(['pricing_type' => PricingType::Free, 'price' => 50000]);
        $this->assertSame(0, $free->effectivePrice());

        $paid = new Product(['pricing_type' => PricingType::Paid, 'price' => 75000, 'promo_price' => null]);
        $this->assertSame(75000, $paid->effectivePrice());

        $promo = new Product(['pricing_type' => PricingType::Paid, 'price' => 75000, 'promo_price' => 59000]);
        $this->assertSame(59000, $promo->effectivePrice());

        $invalidPromo = new Product(['pricing_type' => PricingType::Paid, 'price' => 75000, 'promo_price' => 90000]);
        $this->assertSame(75000, $invalidPromo->effectivePrice());
    }

    public function test_formatted_file_size(): void
    {
        $small = new Product(['file_size' => 512]);
        $this->assertSame('512 B', $small->formattedFileSize());

        $kb = new Product(['file_size' => 2048]);
        $this->assertSame('2.0 KB', $kb->formattedFileSize());

        $mb = new Product(['file_size' => 5242880]);
        $this->assertSame('5.00 MB', $mb->formattedFileSize());
    }

    public function test_order_number_format(): void
    {
        $number = Order::generateOrderNumber();
        $this->assertMatchesRegularExpression('/^MG-\d{8}-[A-F0-9]{6}$/', $number);
    }

    public function test_platform_order_and_ad_fee_calculations(): void
    {
        $setting = new PlatformSetting([
            'order_admin_fee_percent' => 5.0,
            'order_admin_fee_fixed' => 2000,
            'ad_price_catalog_banner' => 50000,
            'ad_price_sidebar_promo' => 35000,
            'ad_price_footer_strip' => 25000,
            'ad_admin_fee_fixed' => 5000,
        ]);

        $this->assertSame(0, $setting->calculateOrderAdminFee(0));
        $this->assertSame(5000, $setting->calculateOrderAdminFee(60000));

        $catalogPricing = $setting->calculateAdTotal(AdPlacement::CatalogBanner);
        $this->assertSame(50000, $catalogPricing['slot_price']);
        $this->assertSame(5000, $catalogPricing['admin_fee']);
        $this->assertSame(55000, $catalogPricing['total_price']);

        $footerPricing = $setting->calculateAdTotal(AdPlacement::FooterStrip);
        $this->assertSame(30000, $footerPricing['total_price']);
    }
}
