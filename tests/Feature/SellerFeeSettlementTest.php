<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\SellerSettlementStatus;
use App\Enums\UserRole;
use App\Livewire\Admin\FeeSettlementsPage as AdminFeeSettlementsPage;
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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SellerFeeSettlementTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_fee_settlement_limit_rejection_and_verification_flow(): void
    {
        Storage::fake('local');

        PlatformSetting::current()->update([
            'max_unpaid_admin_fee' => 10000,
        ]);

        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $otherSeller = User::factory()->create(['role' => UserRole::Seller]);
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

        // Seller submits settlement with private proof
        $proof1 = UploadedFile::fake()->image('setoran-1.jpg', 500, 500);
        Livewire::actingAs($seller)
            ->test(SellerFeeSettlementsPage::class)
            ->set('paymentProof', $proof1)
            ->set('paymentNote', 'Transfer BCA 12000')
            ->call('submitSettlement')
            ->assertHasNoErrors();

        $settlement1 = SellerFeeSettlement::where('seller_id', $seller->id)->firstOrFail();
        $this->assertSame(12000, $settlement1->amount);
        $this->assertSame(1, $settlement1->orders_count);
        $this->assertSame(SellerSettlementStatus::PendingVerification, $settlement1->status);
        $this->assertSame($settlement1->id, $order->fresh()->fee_settlement_id);
        Storage::disk('local')->assertExists($settlement1->payment_proof_path);

        // Proof authorization: other seller forbidden, owning seller and admin allowed
        $this->actingAs($otherSeller)->get(route('settlements.proof', $settlement1))->assertForbidden();
        $this->actingAs($seller)->get(route('settlements.proof', $settlement1))->assertOk();
        $this->actingAs($admin)->get(route('settlements.proof', $settlement1))->assertOk();

        // Admin rejects first settlement -> order is released back to unsettled
        Livewire::actingAs($admin)
            ->test(AdminFeeSettlementsPage::class)
            ->set("rejectNotes.{$settlement1->id}", 'Nominal transfer belum masuk mutasi')
            ->call('rejectSettlement', $settlement1->id)
            ->assertHasNoErrors();

        $this->assertSame(SellerSettlementStatus::Rejected, $settlement1->fresh()->status);
        $this->assertNull($order->fresh()->fee_settlement_id);
        $this->assertSame(12000, $seller->unsettledAdminFeeTotal());

        // Seller resubmits and admin verifies
        $proof2 = UploadedFile::fake()->image('setoran-2.jpg', 500, 500);
        Livewire::actingAs($seller)
            ->test(SellerFeeSettlementsPage::class)
            ->set('paymentProof', $proof2)
            ->call('submitSettlement')
            ->assertHasNoErrors();

        $settlement2 = SellerFeeSettlement::where('seller_id', $seller->id)->latest('id')->firstOrFail();

        Livewire::actingAs($admin)
            ->test(AdminFeeSettlementsPage::class)
            ->call('verifySettlement', $settlement2->id)
            ->assertHasNoErrors();

        $this->assertSame(SellerSettlementStatus::Verified, $settlement2->fresh()->status);
        $this->assertSame(0, $seller->unsettledAdminFeeTotal());
        $this->assertFalse($seller->isOverUnpaidFeeLimit());

        // Admin dashboard reflects settled cash and buyer checkout is unlocked again
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('12.000');

        $this->actingAs($buyer)
            ->get(route('checkout', $product->slug))
            ->assertOk();
    }
}
