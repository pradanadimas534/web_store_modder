<?php

namespace App\Livewire\Store;

use App\Enums\PricingType;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Katalog Mod')]
class CatalogPage extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $game = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $pricing = '';

    #[Url]
    public string $sort = 'latest';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGame(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedPricing(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'game', 'category', 'pricing', 'sort']);
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Product::query()
            ->active()
            ->with(['game', 'category', 'seller.sellerProfile']);

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('short_description', 'like', $term)
                    ->orWhere('compatible_game_version', 'like', $term);
            });
        }

        if ($this->game !== '') {
            $query->whereHas('game', fn (Builder $q) => $q->where('slug', $this->game));
        }

        if ($this->category !== '') {
            $query->whereHas('category', fn (Builder $q) => $q->where('slug', $this->category));
        }

        if (in_array($this->pricing, [PricingType::Free->value, PricingType::Paid->value], true)) {
            $query->where('pricing_type', $this->pricing);
        }

        match ($this->sort) {
            'popular' => $query->orderByDesc('downloads_count'),
            'price_asc' => $query->orderByRaw('COALESCE(promo_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(promo_price, price) DESC'),
            default => $query->latest(),
        };

        return view('livewire.store.catalog-page', [
            'products' => $query->paginate(9),
            'games' => Game::query()->orderBy('sort_order')->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
        ]);
    }
}
