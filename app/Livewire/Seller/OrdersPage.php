<?php

namespace App\Livewire\Seller;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pesanan Masuk')]
class OrdersPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public ?int $approvingOrderId = null;

    public string $approvingOrderNumber = '';

    public ?int $rejectingOrderId = null;

    public string $rejectingOrderNumber = '';

    public string $rejectReason = '';

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openApproveModal(int $orderId): void
    {
        $order = Order::query()->findOrFail($orderId);
        Gate::authorize('verify', $order);

        $this->approvingOrderId = $order->id;
        $this->approvingOrderNumber = $order->order_number;
    }

    public function closeApproveModal(): void
    {
        $this->reset(['approvingOrderId', 'approvingOrderNumber']);
    }

    public function approveOrder(?int $orderId = null): void
    {
        $targetId = $orderId ?? $this->approvingOrderId;
        if ($targetId === null) {
            return;
        }

        $order = Order::query()->findOrFail($targetId);
        Gate::authorize('verify', $order);

        if ($order->status !== OrderStatus::PendingVerification) {
            $this->closeApproveModal();

            return;
        }

        $order->update([
            'status' => OrderStatus::Completed,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        $this->closeApproveModal();
        session()->flash('status', "Pesanan {$order->order_number} berhasil diverifikasi. Akses unduhan pembeli kini aktif.");
    }

    public function openRejectModal(int $orderId): void
    {
        $order = Order::query()->findOrFail($orderId);
        Gate::authorize('verify', $order);

        $this->resetErrorBag('rejectReason');
        $this->rejectReason = '';
        $this->rejectingOrderId = $order->id;
        $this->rejectingOrderNumber = $order->order_number;
    }

    public function closeRejectModal(): void
    {
        $this->resetErrorBag('rejectReason');
        $this->reset(['rejectingOrderId', 'rejectingOrderNumber', 'rejectReason']);
    }

    public function rejectOrder(): void
    {
        if ($this->rejectingOrderId === null) {
            return;
        }

        $order = Order::query()->findOrFail($this->rejectingOrderId);
        Gate::authorize('verify', $order);

        $this->validate(
            ['rejectReason' => ['required', 'string', 'max:300']],
            ['rejectReason.required' => 'Isi alasan penolakan terlebih dahulu.']
        );

        if ($order->status !== OrderStatus::PendingVerification) {
            $this->closeRejectModal();

            return;
        }

        $order->update([
            'status' => OrderStatus::Rejected,
            'rejection_reason' => trim($this->rejectReason),
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $this->closeRejectModal();
        session()->flash('status', "Bukti pembayaran untuk {$order->order_number} ditolak.");
    }

    public function cancelOrder(int $orderId): void
    {
        $order = Order::query()->findOrFail($orderId);
        Gate::authorize('verify', $order);

        if ($order->status !== OrderStatus::PendingPayment) {
            return;
        }

        $order->update([
            'status' => OrderStatus::Cancelled,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        session()->flash('status', "Pesanan {$order->order_number} dibatalkan.");
    }

    public function render(): View
    {
        $query = Order::query()
            ->with(['buyer', 'items'])
            ->where('seller_id', Auth::id())
            ->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.seller.orders-page', [
            'orders' => $query->paginate(10),
        ]);
    }
}
