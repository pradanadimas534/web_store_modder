<?php

namespace App\Livewire\Account;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Wishlist Saya')]
class WishlistPage extends Component
{
    public function remove(int $wishlistId): void
    {
        Wishlist::query()
            ->where('id', $wishlistId)
            ->where('user_id', Auth::id())
            ->delete();
    }

    public function render(): View
    {
        $items = Wishlist::query()
            ->with(['product.game', 'product.category'])
            ->where('user_id', Auth::id())
            ->whereHas('product', fn ($q) => $q->active())
            ->latest()
            ->get();

        return view('livewire.account.wishlist-page', [
            'items' => $items,
        ]);
    }
}
