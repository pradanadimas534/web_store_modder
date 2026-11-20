<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pantau Transaksi')]
class OrdersPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function markCompleted(int $orderId): void
    {
        $order = Order::query()->findOrFail($orderId);
        $order->update([
            'status' => OrderStatus::Completed,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        session()->flash('status', "Transaksi {$order->order_number} ditandai selesai.");
    }

    public function markCancelled(int $orderId): void
    {
        $order = Order::query()->findOrFail($orderId);
        $order->update([
            'status' => OrderStatus::Cancelled,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        session()->flash('status', "Transaksi {$order->order_number} dibatalkan.");
    }

    public function render(): View
    {
        $query = Order::query()->with(['buyer', 'seller.sellerProfile', 'items'])->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.orders-page', [
            'orders' => $query->paginate(12),
        ]);
    }
}
