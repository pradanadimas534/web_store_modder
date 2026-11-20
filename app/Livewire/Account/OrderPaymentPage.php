<?php

namespace App\Livewire\Account;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Detail Pesanan & Pembayaran')]
class OrderPaymentPage extends Component
{
    use WithFileUploads;

    public Order $order;

    #[Validate('required|image|mimes:jpg,jpeg,png,webp|max:4096')]
    public $paymentProof = null;

    #[Validate('nullable|string|max:300')]
    public string $paymentNote = '';

    public string $sellerNote = '';

    public string $rejectReason = '';

    public function mount(string $orderNumber): void
    {
        $order = Order::query()
            ->with(['items.product', 'seller.sellerProfile', 'buyer', 'verifier'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        Gate::authorize('view', $order);

        $this->order = $order;
        $this->paymentNote = (string) ($order->payment_note ?? '');
        $this->sellerNote = (string) ($order->seller_note ?? '');
    }

    public function submitProof(): void
    {
        Gate::authorize('uploadProof', $this->order);

        $this->validate();

        $path = $this->paymentProof->store('payment-proofs', 'local');

        $this->order->update([
            'payment_proof_path' => $path,
            'payment_note' => $this->paymentNote !== '' ? $this->paymentNote : null,
            'status' => OrderStatus::PendingVerification,
            'rejection_reason' => null,
            'paid_at' => now(),
        ]);

        $this->order->refresh()->load(['items.product', 'seller.sellerProfile', 'buyer', 'verifier']);
        $this->reset('paymentProof');

        session()->flash('status', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari modder.');
    }

    public function approveOrder(): void
    {
        Gate::authorize('verify', $this->order);

        if ($this->order->status !== OrderStatus::PendingVerification) {
            return;
        }

        $this->order->update([
            'status' => OrderStatus::Completed,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        $this->order->refresh()->load(['items.product', 'seller.sellerProfile', 'buyer', 'verifier']);
        session()->flash('status', "Pesanan {$this->order->order_number} berhasil diverifikasi. Akses unduhan pembeli kini aktif.");
    }

    public function rejectOrder(): void
    {
        Gate::authorize('verify', $this->order);

        if ($this->order->status !== OrderStatus::PendingVerification) {
            return;
        }

        $reason = trim($this->rejectReason);
        if ($reason === '') {
            $this->addError('rejectReason', 'Isi alasan penolakan terlebih dahulu.');

            return;
        }

        $this->order->update([
            'status' => OrderStatus::Rejected,
            'rejection_reason' => $reason,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $this->rejectReason = '';
        $this->order->refresh()->load(['items.product', 'seller.sellerProfile', 'buyer', 'verifier']);
        session()->flash('status', "Bukti pembayaran untuk {$this->order->order_number} ditolak.");
    }

    public function updatedSellerNote(): void
    {
        $this->saveSellerNote();
    }

    public function saveSellerNote(): void
    {
        Gate::authorize('verify', $this->order);

        $this->validate([
            'sellerNote' => ['nullable', 'string', 'max:500'],
        ]);

        $note = trim($this->sellerNote);
        $this->order->update([
            'seller_note' => $note !== '' ? $note : null,
        ]);

        $this->order->refresh()->load(['items.product', 'seller.sellerProfile', 'buyer', 'verifier']);
    }

    public function render(): View
    {
        $downloadLinks = [];

        if ($this->order->isCompleted()) {
            foreach ($this->order->items as $item) {
                $downloadLinks[$item->id] = URL::temporarySignedRoute(
                    'downloads.paid',
                    now()->plus(minutes: 15),
                    ['orderItem' => $item->id]
                );
            }
        }

        $user = Auth::user();
        $canVerify = $user && Gate::allows('verify', $this->order);

        $backRoute = match (true) {
            $user?->isAdmin() => route('admin.orders'),
            $user?->id === $this->order->seller_id => route('seller.orders'),
            default => route('account.orders'),
        };

        return view('livewire.account.order-payment-page', [
            'downloadLinks' => $downloadLinks,
            'canVerify' => $canVerify,
            'backRoute' => $backRoute,
        ]);
    }
}
