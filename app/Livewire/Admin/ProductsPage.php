<?php

namespace App\Livewire\Admin;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Moderasi Mod')]
class ProductsPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public array $rejectReasons = [];

    public function approve(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);
        $product->update([
            'status' => ProductStatus::Active,
            'rejection_reason' => null,
        ]);

        session()->flash('status', "Mod \"{$product->name}\" disetujui dan tampil di katalog.");
    }

    public function reject(int $productId): void
    {
        $reason = trim((string) ($this->rejectReasons[$productId] ?? ''));
        if ($reason === '') {
            $this->addError("rejectReasons.{$productId}", 'Isi alasan penolakan mod.');

            return;
        }

        $product = Product::query()->findOrFail($productId);
        $product->update([
            'status' => ProductStatus::Rejected,
            'rejection_reason' => $reason,
        ]);

        unset($this->rejectReasons[$productId]);
        session()->flash('status', "Mod \"{$product->name}\" ditolak.");
    }

    public function disable(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);
        $product->update(['status' => ProductStatus::Disabled]);
    }

    public function toggleFeatured(int $productId): void
    {
        $product = Product::query()->findOrFail($productId);
        $product->update(['is_featured' => ! $product->is_featured]);
    }

    public function render(): View
    {
        $query = Product::query()->with(['seller.sellerProfile', 'game', 'category'])->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.products-page', [
            'products' => $query->paginate(10),
        ]);
    }
}
