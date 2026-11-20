<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Admin\SettingsPage;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PlatformFeeSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_configure_transaction_and_advertising_fees(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $seller = User::factory()->create(['role' => UserRole::Seller]);

        $this->actingAs($seller)->get(route('admin.settings'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.settings'))->assertOk();

        Livewire::actingAs($admin)
            ->test(SettingsPage::class)
            ->set('order_admin_fee_percent', 7.5)
            ->set('order_admin_fee_fixed', 3000)
            ->set('ad_price_catalog_banner', 75000)
            ->set('ad_price_sidebar_promo', 45000)
            ->set('ad_price_footer_strip', 30000)
            ->set('ad_admin_fee_fixed', 7500)
            ->set('admin_payment_provider', 'BRI')
            ->set('admin_payment_account_number', '1122334455')
            ->set('admin_payment_account_holder', 'Owner ModGame.id')
            ->call('saveSettings')
            ->assertHasNoErrors();

        $setting = PlatformSetting::current();
        $this->assertSame(7.5, $setting->order_admin_fee_percent);
        $this->assertSame(3000, $setting->order_admin_fee_fixed);
        $this->assertSame(75000, $setting->ad_price_catalog_banner);
        $this->assertSame(7500, $setting->ad_admin_fee_fixed);
        $this->assertSame('BRI', $setting->admin_payment_provider);
    }
}
