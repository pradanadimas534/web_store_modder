<?php

namespace App\Livewire\Seller;

use App\Enums\OrderStatus;
use App\Enums\SellerSettlementStatus;
use App\Models\Order;
use App\Models\PlatformSetting;
use App\Models\SellerFeeSettlement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Setoran Biaya Admin')]
class FeeSettlementsPage extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Validate('required|image|mimes:jpg,jpeg,png,webp|max:4096', message: 'Bukti transfer setoran biaya admin wajib diunggah (JPG, PNG, atau WEBP maks 4 MB).')]
    public $paymentProof = null;

    #[Validate('nullable|string|max:300')]
    public string $paymentNote = '';

    public function submitSettlement(): void
    {
        $this->validate();

        $sellerId = Auth::id();
        $proofPath = $this->paymentProof->store('settlement-proofs', 'local');

        $settlement = DB::transaction(function () use ($sellerId, $proofPath): ?SellerFeeSettlement {
            $unsettledOrders = Order::query()
                ->where('seller_id', $sellerId)
                ->where('status', OrderStatus::Completed)
                ->whereNull('fee_settlement_id')
                ->where('admin_fee', '>', 0)
                ->lockForUpdate()
                ->get();

            if ($unsettledOrders->isEmpty()) {
                return null;
            }

            $totalAmount = (int) $unsettledOrders->sum('admin_fee');

            $settlement = SellerFeeSettlement::create([
                'settlement_number' => SellerFeeSettlement::generateSettlementNumber(),
                'seller_id' => $sellerId,
                'amount' => $totalAmount,
                'orders_count' => $unsettledOrders->count(),
                'status' => SellerSettlementStatus::PendingVerification,
                'payment_proof_path' => $proofPath,
                'payment_note' => $this->paymentNote !== '' ? $this->paymentNote : null,
                'submitted_at' => now(),
            ]);

            Order::query()
                ->whereIn('id', $unsettledOrders->pluck('id'))
                ->update(['fee_settlement_id' => $settlement->id]);

            return $settlement;
        });

        if (! $settlement) {
            $this->addError('paymentProof', 'Tidak ada tagihan biaya admin yang perlu disetorkan saat ini.');

            return;
        }

        $this->reset(['paymentProof', 'paymentNote']);
        session()->flash('status', "Setoran {$settlement->settlement_number} sebesar Rp ".number_format($settlement->amount, 0, ',', '.').' berhasil dikirim untuk verifikasi admin.');
    }

    public function render(): View
    {
        $sellerId = Auth::id();
        $setting = PlatformSetting::current();

        $unsettledOrders = Order::query()
            ->where('seller_id', $sellerId)
            ->where('status', OrderStatus::Completed)
            ->whereNull('fee_settlement_id')
            ->where('admin_fee', '>', 0)
            ->latest()
            ->get();

        $unsettledTotal = (int) $unsettledOrders->sum('admin_fee');

        $verifiedTotal = (int) SellerFeeSettlement::query()
            ->where('seller_id', $sellerId)
            ->where('status', SellerSettlementStatus::Verified)
            ->sum('amount');

        $settlements = SellerFeeSettlement::query()
            ->with('verifier')
            ->where('seller_id', $sellerId)
            ->latest()
            ->paginate(10);

        return view('livewire.seller.fee-settlements-page', [
            'setting' => $setting,
            'unsettledOrders' => $unsettledOrders,
            'unsettledTotal' => $unsettledTotal,
            'verifiedTotal' => $verifiedTotal,
            'isOverLimit' => $setting->max_unpaid_admin_fee > 0 && $unsettledTotal >= $setting->max_unpaid_admin_fee,
            'settlements' => $settlements,
        ]);
    }
}
