<?php

namespace App\Livewire\Store;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ProductDetailPage extends Component
{
    public Product $product;

    public bool $isWishlisted = false;

    public function mount(string $slug): void
    {
        $product = Product::query()
            ->with(['game', 'category', 'seller.sellerProfile', 'seller.paymentMethods' => fn ($q) => $q->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = Auth::user();
        $canViewUnpublished = $user && ($user->isAdmin() || $product->seller_id === $user->id);

        if (! $product->isActive() && ! $canViewUnpublished) {
            abort(404);
        }

        $this->product = $product;

        if ($user) {
            $this->isWishlisted = Wishlist::query()
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
        }
    }

    public function toggleWishlist(): void
    {
        $user = Auth::user();

        if (! $user) {
            $this->redirectRoute('login');

            return;
        }

        $existing = Wishlist::query()
            ->where('user_id', $user->id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->isWishlisted = false;

            return;
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $this->product->id,
        ]);

        $this->isWishlisted = true;
    }

    public function render(): View
    {
        $user = Auth::user();
        $paidDownloadUrl = null;

        if ($user && $this->product->isPaid()) {
            $purchasedItem = OrderItem::query()
                ->where('product_id', $this->product->id)
                ->whereHas('order', fn ($q) => $q
                    ->where('buyer_id', $user->id)
                    ->where('status', OrderStatus::Completed)
                )
                ->latest()
                ->first();

            if ($purchasedItem) {
                $paidDownloadUrl = URL::temporarySignedRoute(
                    'downloads.paid',
                    now()->plus(minutes: 15),
                    ['orderItem' => $purchasedItem->id]
                );
            }
        }

        $relatedProducts = Product::query()
            ->active()
            ->with(['game', 'category'])
            ->where('id', '!=', $this->product->id)
            ->where('game_id', $this->product->game_id)
            ->limit(3)
            ->get();

        return view('livewire.store.product-detail-page', [
            'paidDownloadUrl' => $paidDownloadUrl,
            'relatedProducts' => $relatedProducts,
            'sellerHasPayments' => $this->product->seller->paymentMethods->isNotEmpty(),
            'sellerOverFeeLimit' => $this->product->seller->isOverUnpaidFeeLimit(),
        ])->title($this->product->name);
    }
}
