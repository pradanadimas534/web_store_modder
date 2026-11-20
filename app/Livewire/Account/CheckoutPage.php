<?php

namespace App\Livewire\Account;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\SellerPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Checkout Mod')]
class CheckoutPage extends Component
{
    public Product $product;

    public ?int $selectedPaymentMethodId = null;

    public bool $agreeTerms = false;

    public function mount(Product $product): void
    {
        if (! $product->isActive() || ! $product->isPaid()) {
            abort(404, 'Produk ini tidak tersedia untuk checkout.');
        }

        if ($product->seller_id === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat membeli mod milik toko Anda sendiri.');
            $this->redirectRoute('products.show', $product->slug);

            return;
        }

        if ($product->seller->isOverUnpaidFeeLimit()) {
            session()->flash('error', 'Toko modder ini sedang menyelesaikan kewajiban administrasi platform. Pembelian mod berbayar sementara dijeda.');
            $this->redirectRoute('products.show', $product->slug);

            return;
        }

        $this->product = $product->load(['game', 'category', 'seller.sellerProfile']);

        $firstMethod = SellerPaymentMethod::query()
            ->where('user_id', $product->seller_id)
            ->where('is_active', true)
            ->first();

        $this->selectedPaymentMethodId = $firstMethod?->id;
    }

    public function createOrder(): void
    {
        $this->validate([
            'selectedPaymentMethodId' => ['required', 'integer'],
            'agreeTerms' => ['accepted'],
        ], [
            'selectedPaymentMethodId.required' => 'Pilih metode pembayaran milik modder terlebih dahulu.',
            'agreeTerms.accepted' => 'Anda harus menyetujui ketentuan pembelian produk digital.',
        ]);

        // Re-fetch authoritative product price, platform fee, and active payment method from database.
        $freshProduct = Product::query()
            ->active()
            ->findOrFail($this->product->id);

        if (! $freshProduct->isPaid()) {
            abort(400, 'Produk ini bukan produk berbayar.');
        }

        $paymentMethod = SellerPaymentMethod::query()
            ->where('id', $this->selectedPaymentMethodId)
            ->where('user_id', $freshProduct->seller_id)
            ->where('is_active', true)
            ->first();

        if (! $paymentMethod) {
            $this->addError('selectedPaymentMethodId', 'Metode pembayaran modder tidak valid atau sudah nonaktif.');

            return;
        }

        $setting = PlatformSetting::current();

        $order = DB::transaction(function () use ($freshProduct, $paymentMethod, $setting): Order {
            $subtotal = $freshProduct->effectivePrice();
            $adminFee = $setting->calculateOrderAdminFee($subtotal);
            $total = $subtotal + $adminFee;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'buyer_id' => Auth::id(),
                'seller_id' => $freshProduct->seller_id,
                'seller_payment_method_id' => $paymentMethod->id,
                'payment_method_snapshot' => $paymentMethod->toSnapshot(),
                'subtotal' => $subtotal,
                'admin_fee' => $adminFee,
                'total' => $total,
                'seller_net_amount' => $subtotal,
                'status' => OrderStatus::PendingPayment,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $freshProduct->id,
                'product_name_snapshot' => $freshProduct->name,
                'price_snapshot' => $subtotal,
            ]);

            return $order;
        });

        session()->flash('status', 'Pesanan dibuat. Lakukan pembayaran sesuai total tagihan lalu unggah bukti transfer.');
        $this->redirectRoute('orders.payment', $order->order_number);
    }

    public function render(): View
    {
        $setting = PlatformSetting::current();
        $subtotal = $this->product->effectivePrice();
        $adminFee = $setting->calculateOrderAdminFee($subtotal);

        $paymentMethods = SellerPaymentMethod::query()
            ->where('user_id', $this->product->seller_id)
            ->where('is_active', true)
            ->get();

        return view('livewire.account.checkout-page', [
            'paymentMethods' => $paymentMethods,
            'subtotal' => $subtotal,
            'adminFee' => $adminFee,
            'total' => $subtotal + $adminFee,
            'feePercent' => $setting->order_admin_fee_percent,
            'feeFixed' => $setting->order_admin_fee_fixed,
        ]);
    }
}
