<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Livewire\Account\CheckoutPage;
use App\Livewire\Account\OrderPaymentPage;
use App\Livewire\Seller\OrdersPage as SellerOrdersPage;
use App\Livewire\Seller\PaymentMethodsPage;
use App\Models\Category;
use App\Models\Game;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerPaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class PaidModTransactionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_paid_mod_flow_and_download_protection(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('mods/pantura-pro.zip', 'paid-mod-binary-content');

        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $otherSeller = User::factory()->create(['role' => UserRole::Seller]);
        $buyer = User::factory()->create(['role' => UserRole::User]);
        $otherBuyer = User::factory()->create(['role' => UserRole::User]);

        $game = Game::create(['name' => 'ETS2', 'slug' => 'ets2', 'short_code' => 'ETS2']);
        $category = Category::create(['name' => 'Map', 'slug' => 'map']);

        $product = Product::create([
            'seller_id' => $seller->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'name' => 'Map Pantura Pro',
            'slug' => 'map-pantura-pro',
            'short_description' => 'Map jalur pantura berbayar.',
            'description' => 'Deskripsi lengkap map jalur pantura berbayar untuk ETS2.',
            'pricing_type' => PricingType::Paid,
            'price' => 75000,
            'promo_price' => 60000,
            'mod_version' => '2.0.0',
            'compatible_game_version' => 'ETS2 1.53',
            'file_path' => 'mods/pantura-pro.zip',
            'file_original_name' => 'pantura-pro.zip',
            'file_size' => 23,
            'status' => ProductStatus::Active,
        ]);

        // 1. Seller configures payment method via web
        Livewire::actingAs($seller)
            ->test(PaymentMethodsPage::class)
            ->set('provider_name', 'BCA')
            ->set('account_number', '9988776655')
            ->set('account_holder', 'Nusantara Mod')
            ->set('instructions', 'Transfer tepat sesuai nominal')
            ->call('saveMethod')
            ->assertHasNoErrors();

        $method = SellerPaymentMethod::where('user_id', $seller->id)->firstOrFail();

        // 2. Buyer checks out and backend computes authoritative effective price (60000)
        Livewire::actingAs($buyer)
            ->test(CheckoutPage::class, ['product' => $product])
            ->set('selectedPaymentMethodId', $method->id)
            ->set('agreeTerms', true)
            ->call('createOrder')
            ->assertHasNoErrors();

        $order = Order::with('items')->where('buyer_id', $buyer->id)->firstOrFail();
        $orderItem = $order->items->firstOrFail();

        // Subtotal 60000 + default admin fee (5% = 3000 + fixed 2000 = 5000) = 65000
        $this->assertSame(60000, $order->subtotal);
        $this->assertSame(5000, $order->admin_fee);
        $this->assertSame(65000, $order->total);
        $this->assertSame(60000, $order->seller_net_amount);
        $this->assertSame(OrderStatus::PendingPayment, $order->status);
        $this->assertSame('BCA', $order->payment_method_snapshot['provider_name']);

        // 3. Buyer cannot download before verification even with valid signed URL
        $signedUrl = URL::temporarySignedRoute('downloads.paid', now()->plus(minutes: 15), ['orderItem' => $orderItem->id]);
        $this->actingAs($buyer)->get($signedUrl)->assertForbidden();

        // 4. Buyer uploads payment proof
        $proofFile = UploadedFile::fake()->image('bukti-transfer.jpg', 600, 800);
        Livewire::actingAs($buyer)
            ->test(OrderPaymentPage::class, ['orderNumber' => $order->order_number])
            ->set('paymentProof', $proofFile)
            ->set('paymentNote', 'Transfer dari BCA a.n. Pembeli')
            ->call('submitProof')
            ->assertHasNoErrors();

        $order->refresh();
        $this->assertSame(OrderStatus::PendingVerification, $order->status);
        $this->assertNotNull($order->payment_proof_path);
        Storage::disk('local')->assertExists($order->payment_proof_path);

        // 5. Another seller cannot approve or view proof of this order
        Livewire::actingAs($otherSeller)
            ->test(SellerOrdersPage::class)
            ->call('approveOrder', $order->id)
            ->assertForbidden();

        $this->actingAs($otherSeller)->get(route('orders.proof', $order))->assertForbidden();

        // 6. Owning seller verifies payment
        Livewire::actingAs($seller)
            ->test(SellerOrdersPage::class)
            ->call('approveOrder', $order->id)
            ->assertHasNoErrors();

        $this->assertSame(OrderStatus::Completed, $order->fresh()->status);

        // 7. Unsigned download request is rejected; other buyer is rejected; owning buyer succeeds
        $this->actingAs($buyer)->get(route('downloads.paid', ['orderItem' => $orderItem->id]))->assertForbidden();
        $this->actingAs($otherBuyer)->get($signedUrl)->assertForbidden();

        $downloadResponse = $this->actingAs($buyer)->get($signedUrl);
        $downloadResponse->assertOk();
        $downloadResponse->assertDownload('pantura-pro.zip');
    }

    public function test_seller_cannot_approve_order_without_payment_proof_but_can_cancel(): void
    {
        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $buyer = User::factory()->create(['role' => UserRole::User]);

        $unpaidOrder = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'payment_method_snapshot' => ['provider_name' => 'BCA'],
            'subtotal' => 50000,
            'admin_fee' => 4500,
            'total' => 54500,
            'seller_net_amount' => 50000,
            'status' => OrderStatus::PendingPayment,
        ]);

        Livewire::actingAs($seller)
            ->test(SellerOrdersPage::class)
            ->call('approveOrder', $unpaidOrder->id)
            ->assertHasNoErrors();

        $this->assertSame(OrderStatus::PendingPayment, $unpaidOrder->fresh()->status);

        Livewire::actingAs($seller)
            ->test(SellerOrdersPage::class)
            ->call('cancelOrder', $unpaidOrder->id)
            ->assertHasNoErrors();

        $this->assertSame(OrderStatus::Cancelled, $unpaidOrder->fresh()->status);
    }

    public function test_seller_can_save_note_on_order_detail_and_buyer_cannot(): void
    {
        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $buyer = User::factory()->create(['role' => UserRole::User]);

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'payment_method_snapshot' => ['provider_name' => 'BCA'],
            'subtotal' => 50000,
            'admin_fee' => 4500,
            'total' => 54500,
            'seller_net_amount' => 50000,
            'status' => OrderStatus::PendingPayment,
        ]);

        // Buyer cannot update seller note
        Livewire::actingAs($buyer)
            ->test(OrderPaymentPage::class, ['orderNumber' => $order->order_number])
            ->set('sellerNote', 'Catatan ilegal')
            ->assertForbidden();

        // Owning seller auto-saves note when typing and buyer can see it
        Livewire::actingAs($seller)
            ->test(OrderPaymentPage::class, ['orderNumber' => $order->order_number])
            ->set('sellerNote', 'Password arsip mod: nusantara2026')
            ->assertHasNoErrors();

        $this->assertSame('Password arsip mod: nusantara2026', $order->fresh()->seller_note);

        $this->actingAs($buyer)
            ->get(route('orders.payment', $order->order_number))
            ->assertOk()
            ->assertSee('Password arsip mod: nusantara2026');
    }
}
