<?php

namespace Tests\Feature;

use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Livewire\Store\CatalogPage;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StorefrontAndDownloadTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $overrides = []): Product
    {
        Storage::fake('local');
        Storage::disk('local')->put('mods/test-mod.zip', 'fake-mod-archive-bytes');

        $seller = $overrides['seller'] ?? User::factory()->create(['role' => UserRole::Seller]);
        $game = $overrides['game'] ?? Game::create([
            'name' => 'Euro Truck Simulator 2',
            'slug' => 'ets2-'.uniqid(),
            'short_code' => 'ETS2',
        ]);
        $category = $overrides['category'] ?? Category::create([
            'name' => 'Map',
            'slug' => 'map-'.uniqid(),
        ]);

        unset($overrides['seller'], $overrides['game'], $overrides['category']);

        return Product::create(array_merge([
            'seller_id' => $seller->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'name' => 'Mod Uji Coba',
            'slug' => 'mod-uji-coba-'.uniqid(),
            'short_description' => 'Deskripsi singkat mod uji coba untuk pengujian.',
            'description' => 'Deskripsi panjang mod uji coba untuk pengujian otomatis PHPUnit.',
            'pricing_type' => PricingType::Free,
            'price' => 0,
            'mod_version' => '1.0.0',
            'compatible_game_version' => 'ETS2 1.53',
            'file_path' => 'mods/test-mod.zip',
            'file_original_name' => 'test-mod.zip',
            'file_size' => 22,
            'status' => ProductStatus::Active,
        ], $overrides));
    }

    public function test_guest_can_view_public_pages(): void
    {
        $product = $this->createProduct(['name' => 'Skin Logistik Gratis']);

        $this->get(route('home'))->assertOk()->assertSee('ModGame');
        $this->get(route('catalog'))->assertOk()->assertSee('Skin Logistik Gratis');
        $this->get(route('products.show', $product->slug))->assertOk()->assertSee('Skin Logistik Gratis');
        $this->get(route('ads.submit'))->assertOk()->assertSee('Pasang Iklan');
    }

    public function test_guest_can_filter_catalog_by_pricing_type(): void
    {
        $this->createProduct(['name' => 'Mod Gratis Satu', 'pricing_type' => PricingType::Free, 'price' => 0]);
        $this->createProduct(['name' => 'Mod Berbayar Satu', 'pricing_type' => PricingType::Paid, 'price' => 45000]);

        Livewire::test(CatalogPage::class)
            ->set('pricing', 'free')
            ->assertSee('Mod Gratis Satu')
            ->assertDontSee('Mod Berbayar Satu')
            ->set('pricing', 'paid')
            ->assertSee('Mod Berbayar Satu')
            ->assertDontSee('Mod Gratis Satu');
    }

    public function test_guest_can_download_free_mod_directly_and_cannot_download_paid_mod_for_free(): void
    {
        $freeMod = $this->createProduct(['pricing_type' => PricingType::Free, 'price' => 0]);
        $paidMod = $this->createProduct(['pricing_type' => PricingType::Paid, 'price' => 50000]);

        $response = $this->get(route('downloads.free', $freeMod->slug));
        $response->assertOk();
        $response->assertDownload('test-mod.zip');

        $this->assertSame(1, $freeMod->fresh()->downloads_count);
        $this->assertDatabaseHas('downloads', ['product_id' => $freeMod->id]);

        $this->get(route('downloads.free', $paidMod->slug))->assertForbidden();
    }

    public function test_pending_product_is_hidden_from_public(): void
    {
        $pending = $this->createProduct(['status' => ProductStatus::PendingReview]);

        $this->get(route('products.show', $pending->slug))->assertNotFound();
    }
}
