<?php

namespace App\Livewire\Store;

use App\Enums\PricingType;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Beranda')]
class HomePage extends Component
{
    public function render(): View
    {
        $games = Game::query()->orderBy('sort_order')->get();
        $categories = Category::query()->orderBy('sort_order')->get();

        $featuredProducts = Product::query()
            ->active()
            ->with(['game', 'category', 'seller.sellerProfile'])
            ->orderByDesc('is_featured')
            ->orderByDesc('downloads_count')
            ->limit(4)
            ->get();

        $freeProducts = Product::query()
            ->active()
            ->where('pricing_type', PricingType::Free)
            ->with(['game', 'category'])
            ->latest()
            ->limit(4)
            ->get();

        return view('livewire.store.home-page', [
            'games' => $games,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'freeProducts' => $freeProducts,
        ]);
    }
}
