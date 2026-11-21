<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\SellerSettlementStatus;
use App\Enums\UserRole;
use App\Livewire\Seller\FeeSettlementsPage as SellerFeeSettlementsPage;
use App\Models\Category;
use App\Models\Game;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\SellerFeeSettlement;
use App\Models\SellerPaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SellerFeeSettlementTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_direct_fee_settlement_payment_to_admin_account_unlocks_checkout(): void
    {
        PlatformSetting::current()->update([
            'max_unpaid_admin_fee' => 10000,
        ]);

        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $buyer = User::factory()->create(['role' => UserRole::User]);

        $game = Game::create(['name' => 'ETS2', 'slug' => 'ets2', 'short_code' => 'ETS2']);
        $category = Category::create(['name' => 'Map', 'slug' => 'map']);

        $product = Product::create([
            'seller_id' => $seller->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'name' => 'Map Jawa Timur',
            'slug' => 'map-jawa-timur',
            'short_description' => 'Map jalur Jawa Timur.',
            'description' => 'Deskripsi lengkap map jalur Jawa Timur untuk ETS2.',
            'pricing_type' => PricingType::Paid,
            'price' => 100000,
            'mod_version' => '1.0.0',
            'compatible_game_version' => 'ETS2 1.53',
            'file_path' => 'mods/jatim.zip',
            'file_original_name' => 'jatim.zip',
            'file_size' => 1000,
            'status' => ProductStatus::Active,
        ]);

        SellerPaymentMethod::create([
            'user_id' => $seller->id,
            'provider_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'Modder Jatim',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'payment_method_snapshot' => ['provider_name' => 'BCA'],
            'subtotal' => 100000,
            'admin_fee' => 12000,
            'total' => 112000,
            'seller_net_amount' => 100000,
            'status' => OrderStatus::Completed,
            'verified_at' => now(),
        ]);

        // Unpaid fee (12000) >= limit (10000), so checkout is paused for buyer
        $this->assertSame(12000, $seller->unsettledAdminFeeTotal());
        $this->assertTrue($seller->isOverUnpaidFeeLimit());
        $this->actingAs($buyer)
            ->get(route('checkout', $product->slug))
            ->assertRedirect(route('products.show', $product->slug));

        // Seller opens direct payment modal, wrong nominal fails, exact nominal (12000) immediately succeeds
        Livewire::actingAs($seller)
            ->test(SellerFeeSettlementsPage::class)
            ->call('openPaymentGateway')
            ->assertSet('showPaymentGateway', true)
            ->set('paymentAmount', 5000)
            ->call('paySettlement')
            ->assertHasErrors('paymentAmount')
            ->set('paymentAmount', 12000)
            ->call('paySettlement')
            ->assertHasNoErrors()
            ->assertSet('showPaymentGateway', false);

        $settlement = SellerFeeSettlement::where('seller_id', $seller->id)->firstOrFail();
        $this->assertSame(12000, $settlement->amount);
        $this->assertSame(1, $settlement->orders_count);
        $this->assertSame(SellerSettlementStatus::Verified, $settlement->status);
        $this->assertSame($settlement->id, $order->fresh()->fee_settlement_id);
        $this->assertSame(0, $seller->unsettledAdminFeeTotal());
        $this->assertFalse($seller->isOverUnpaidFeeLimit());

        // Admin dashboard immediately reflects settled cash and buyer checkout is unlocked
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('12.000');

        $this->actingAs($buyer)
            ->get(route('checkout', $product->slug))
            ->assertOk();
    }
}
