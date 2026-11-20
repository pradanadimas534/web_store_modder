<?php

namespace App\Livewire\Seller;

use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kelola Mod Saya')]
class ProductsPage extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public int $game_id = 0;

    public int $category_id = 0;

    public string $pricing_type = 'free';

    public int $price = 0;

    public ?int $promo_price = null;

    public string $short_description = '';

    public string $description = '';

    public string $mod_version = '1.0.0';

    public string $compatible_game_version = '';

    public string $dependencies = '';

    public string $install_guide = '';

    public string $changelog = '';

    public $thumbnail = null;

    public $modFile = null;

    public function startCreate(): void
    {
        $this->resetForm();
        $this->game_id = (int) (Game::query()->orderBy('sort_order')->value('id') ?? 0);
        $this->category_id = (int) (Category::query()->orderBy('sort_order')->value('id') ?? 0);
        $this->showForm = true;
    }

    public function startEdit(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);
        Gate::authorize('update', $product);

        $this->editingId = $product->id;
        $this->name = $product->name;
        $this->game_id = $product->game_id;
        $this->category_id = $product->category_id;
        $this->pricing_type = $product->pricing_type->value;
        $this->price = $product->price;
        $this->promo_price = $product->promo_price;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->mod_version = $product->mod_version;
        $this->compatible_game_version = $product->compatible_game_version;
        $this->dependencies = (string) ($product->dependencies ?? '');
        $this->install_guide = (string) ($product->install_guide ?? '');
        $this->changelog = (string) ($product->changelog ?? '');
        $this->thumbnail = null;
        $this->modFile = null;
        $this->showForm = true;
    }

    public function saveProduct(): void
    {
        $isPaid = $this->pricing_type === PricingType::Paid->value;

        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'game_id' => ['required', 'exists:games,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'pricing_type' => ['required', 'in:free,paid'],
            'price' => $isPaid ? ['required', 'integer', 'min:1000', 'max:100000000'] : ['integer', 'min:0'],
            'promo_price' => ['nullable', 'integer', 'min:1000', 'lt:price'],
            'short_description' => ['required', 'string', 'min:10', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'mod_version' => ['required', 'string', 'max:40'],
            'compatible_game_version' => ['required', 'string', 'max:80'],
            'dependencies' => ['nullable', 'string', 'max:255'],
            'install_guide' => ['nullable', 'string', 'max:3000'],
            'changelog' => ['nullable', 'string', 'max:3000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'modFile' => $this->editingId === null
                ? ['required', 'file', 'mimes:zip,rar,7z,scs', 'max:51200']
                : ['nullable', 'file', 'mimes:zip,rar,7z,scs', 'max:51200'],
        ];

        $this->validate($rules);

        $data = [
            'game_id' => $this->game_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'pricing_type' => PricingType::from($this->pricing_type),
            'price' => $isPaid ? $this->price : 0,
            'promo_price' => $isPaid && $this->promo_price ? $this->promo_price : null,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'mod_version' => $this->mod_version,
            'compatible_game_version' => $this->compatible_game_version,
            'dependencies' => $this->dependencies !== '' ? $this->dependencies : null,
            'install_guide' => $this->install_guide !== '' ? $this->install_guide : null,
            'changelog' => $this->changelog !== '' ? $this->changelog : null,
        ];

        if ($this->thumbnail) {
            $data['thumbnail_path'] = $this->thumbnail->store('thumbnails', 'public');
        }

        if ($this->modFile) {
            $storedPath = $this->modFile->store('mods', 'local');
            $data['file_path'] = $storedPath;
            $data['file_original_name'] = $this->modFile->getClientOriginalName();
            $data['file_size'] = Storage::disk('local')->size($storedPath);
            $data['file_checksum'] = hash_file('sha256', Storage::disk('local')->path($storedPath));
        }

        if ($this->editingId !== null) {
            $product = Product::query()->findOrFail($this->editingId);
            Gate::authorize('update', $product);
            $product->update($data);
            session()->flash('status', 'Data mod berhasil diperbarui.');
        } else {
            $slugBase = Str::slug($this->name);
            $data['seller_id'] = Auth::id();
            $data['slug'] = $slugBase.'-'.strtolower(Str::random(5));
            $data['status'] = ProductStatus::PendingReview;

            Product::create($data);
            session()->flash('status', 'Mod berhasil diunggah dan menunggu review admin.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function deleteProduct(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);
        Gate::authorize('delete', $product);
        $product->delete();

        session()->flash('status', 'Mod berhasil dihapus dari daftar aktif.');
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'name',
            'pricing_type',
            'price',
            'promo_price',
            'short_description',
            'description',
            'compatible_game_version',
            'dependencies',
            'install_guide',
            'changelog',
            'thumbnail',
            'modFile',
        ]);
        $this->pricing_type = 'free';
        $this->mod_version = '1.0.0';
    }

    public function render(): View
    {
        return view('livewire.seller.products-page', [
            'products' => Product::query()
                ->with(['game', 'category'])
                ->where('seller_id', Auth::id())
                ->latest()
                ->paginate(10),
            'games' => Game::query()->orderBy('sort_order')->get(),
            'categories' => Category::query()->orderBy('sort_order')->get(),
        ]);
    }
}
