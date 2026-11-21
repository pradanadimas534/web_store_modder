<?php

namespace Tests\Feature;

use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Livewire\Admin\CatalogMasterPage;
use App\Livewire\Admin\ProductsPage as AdminProductsPage;
use App\Livewire\Admin\UsersPage as AdminUsersPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Seller\ProductsPage as SellerProductsPage;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoleAndIsolationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_middleware_enforces_route_boundaries(): void
    {
        $buyer = User::factory()->create(['role' => UserRole::User]);
        $seller = User::factory()->create(['role' => UserRole::Seller]);
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->actingAs($buyer)->get(route('seller.dashboard'))->assertForbidden();
        $this->actingAs($buyer)->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs($seller)->get(route('seller.dashboard'))->assertOk();
        $this->actingAs($seller)->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('seller.dashboard'))->assertForbidden();
    }

    public function test_seller_cannot_edit_or_delete_another_sellers_mod(): void
    {
        $sellerA = User::factory()->create(['role' => UserRole::Seller]);
        $sellerB = User::factory()->create(['role' => UserRole::Seller]);
        $game = Game::create(['name' => 'ATS', 'slug' => 'ats', 'short_code' => 'ATS']);
        $category = Category::create(['name' => 'Truck', 'slug' => 'truck']);

        $modA = Product::create([
            'seller_id' => $sellerA->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'name' => 'Truck Milik Seller A',
            'slug' => 'truck-seller-a',
            'short_description' => 'Mod milik seller A.',
            'description' => 'Deskripsi lengkap mod milik seller A.',
            'pricing_type' => PricingType::Free,
            'price' => 0,
            'mod_version' => '1.0.0',
            'compatible_game_version' => 'ATS 1.53',
            'file_path' => 'mods/a.zip',
            'file_original_name' => 'a.zip',
            'file_size' => 100,
            'status' => ProductStatus::Active,
        ]);

        Livewire::actingAs($sellerB)
            ->test(SellerProductsPage::class)
            ->call('startEdit', $modA->id)
            ->assertForbidden();

        Livewire::actingAs($sellerB)
            ->test(SellerProductsPage::class)
            ->call('deleteProduct', $modA->id)
            ->assertForbidden();
    }

    public function test_admin_can_create_client_modder_moderate_mod_and_deactivate_user(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        Livewire::actingAs($admin)
            ->test(AdminUsersPage::class)
            ->set('newName', 'Modder Baru')
            ->set('newStoreName', 'Bengkel Simulator')
            ->set('newEmail', 'modderbaru@example.com')
            ->set('newPassword', 'secret12345')
            ->call('createSeller')
            ->assertHasNoErrors();

        $createdSeller = User::where('email', 'modderbaru@example.com')->firstOrFail();
        $this->assertSame(UserRole::Seller, $createdSeller->role);
        $this->assertDatabaseHas('seller_profiles', [
            'user_id' => $createdSeller->id,
            'store_name' => 'Bengkel Simulator',
        ]);

        $game = Game::create(['name' => 'ETS2', 'slug' => 'ets2', 'short_code' => 'ETS2']);
        $category = Category::create(['name' => 'Skin', 'slug' => 'skin']);

        $pendingMod = Product::create([
            'seller_id' => $createdSeller->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'name' => 'Skin Baru',
            'slug' => 'skin-baru',
            'short_description' => 'Skin baru menunggu review.',
            'description' => 'Deskripsi lengkap skin baru menunggu review admin.',
            'pricing_type' => PricingType::Free,
            'price' => 0,
            'mod_version' => '1.0.0',
            'compatible_game_version' => 'ETS2 1.53',
            'file_path' => 'mods/skin.zip',
            'file_original_name' => 'skin.zip',
            'file_size' => 100,
            'status' => ProductStatus::PendingReview,
        ]);

        Livewire::actingAs($admin)
            ->test(AdminProductsPage::class)
            ->call('approve', $pendingMod->id)
            ->assertHasNoErrors();

        $this->assertSame(ProductStatus::Active, $pendingMod->fresh()->status);

        // Deactivate seller and ensure login is blocked
        Livewire::actingAs($admin)
            ->test(AdminUsersPage::class)
            ->call('toggleActive', $createdSeller->id);

        $this->assertFalse($createdSeller->fresh()->is_active);

        Livewire::test(LoginPage::class)
            ->set('email', 'modderbaru@example.com')
            ->set('password', 'secret12345')
            ->call('authenticate')
            ->assertHasErrors('email');

        // Admin can dynamically add new games and categories
        Livewire::actingAs($admin)
            ->test(CatalogMasterPage::class)
            ->set('gameName', 'Cyberpunk 2077')
            ->set('gameShortCode', 'CP2077')
            ->set('gameCoverPath', 'assets/game-covers/open-world.svg')
            ->call('addGame')
            ->assertHasNoErrors()
            ->set('categoryName', 'Shader & Reshade')
            ->call('addCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('games', ['slug' => 'cyberpunk-2077', 'short_code' => 'CP2077']);
        $this->assertDatabaseHas('categories', ['slug' => 'shader-reshade']);
    }
}
