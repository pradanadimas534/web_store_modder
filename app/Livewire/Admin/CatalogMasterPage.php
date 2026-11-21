<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Kelola Game & Kategori')]
class CatalogMasterPage extends Component
{
    public string $gameName = '';

    public string $gameShortCode = '';

    public string $gameCoverPath = 'assets/game-covers/open-world.svg';

    public string $gameDescription = '';

    public string $categoryName = '';

    public string $categoryDescription = '';

    public function addGame(): void
    {
        $this->validate([
            'gameName' => ['required', 'string', 'min:2', 'max:100'],
            'gameShortCode' => ['required', 'string', 'min:2', 'max:16'],
            'gameCoverPath' => ['required', 'string', 'max:255'],
            'gameDescription' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($this->gameName);
        if (Game::where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower(Str::random(4));
        }

        Game::create([
            'name' => $this->gameName,
            'slug' => $slug,
            'short_code' => strtoupper($this->gameShortCode),
            'cover_path' => $this->gameCoverPath,
            'description' => $this->gameDescription !== '' ? $this->gameDescription : null,
            'sort_order' => (int) Game::max('sort_order') + 1,
        ]);

        $this->reset(['gameName', 'gameShortCode', 'gameDescription']);
        session()->flash('status', 'Game baru berhasil ditambahkan ke katalog.');
    }

    public function deleteGame(int $gameId): void
    {
        $game = Game::withCount('products')->findOrFail($gameId);
        if ($game->products_count > 0) {
            session()->flash('error', 'Game tidak dapat dihapus karena masih memiliki mod terkait.');

            return;
        }

        $game->delete();
        session()->flash('status', 'Game berhasil dihapus.');
    }

    public function addCategory(): void
    {
        $this->validate([
            'categoryName' => ['required', 'string', 'min:2', 'max:80'],
            'categoryDescription' => ['nullable', 'string', 'max:255'],
        ]);

        $slug = Str::slug($this->categoryName);
        if (Category::where('slug', $slug)->exists()) {
            $slug .= '-'.strtolower(Str::random(4));
        }

        Category::create([
            'name' => $this->categoryName,
            'slug' => $slug,
            'description' => $this->categoryDescription !== '' ? $this->categoryDescription : null,
            'sort_order' => (int) Category::max('sort_order') + 1,
        ]);

        $this->reset(['categoryName', 'categoryDescription']);
        session()->flash('status', 'Kategori baru berhasil ditambahkan.');
    }

    public function deleteCategory(int $categoryId): void
    {
        $category = Category::withCount('products')->findOrFail($categoryId);
        if ($category->products_count > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki mod terkait.');

            return;
        }

        $category->delete();
        session()->flash('status', 'Kategori berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.admin.catalog-master-page', [
            'games' => Game::withCount('products')->orderBy('sort_order')->get(),
            'categories' => Category::withCount('products')->orderBy('sort_order')->get(),
            'availableCovers' => [
                'assets/game-covers/open-world.svg' => 'Open World / Action',
                'assets/game-covers/sandbox.svg' => 'Sandbox / Voxel',
                'assets/game-covers/fantasy-rpg.svg' => 'Fantasy / Anime RPG',
                'assets/game-covers/simulation.svg' => 'Vehicle / Simulation',
                'assets/game-covers/racing.svg' => 'Street Racing / Drift',
                'assets/game-covers/survival.svg' => 'Survival / Life Sim',
            ],
        ]);
    }
}
