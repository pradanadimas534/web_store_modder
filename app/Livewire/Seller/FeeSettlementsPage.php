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
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Setoran Biaya Admin')]
class FeeSettlementsPage extends Component
{
    use WithPagination;

    public bool $showPaymentGateway = false;

    public int|string $paymentAmount = '';

    public function openPaymentGateway(): void
    {
        $unsettledTotal = Auth::user()->unsettledAdminFeeTotal();
        if ($unsettledTotal <= 0) {
            return;
        }

        $this->resetErrorBag('paymentAmount');
        $this->paymentAmount = '';
        $this->showPaymentGateway = true;
    }

    public function closePaymentGateway(): void
    {
        $this->resetErrorBag('paymentAmount');
        $this->reset(['showPaymentGateway', 'paymentAmount']);
    }

    public function paySettlement(): void
    {
        $this->validate([
            'paymentAmount' => ['required', 'integer', 'min:1'],
        ], [
            'paymentAmount.required' => 'Masukkan nominal pembayaran terlebih dahulu.',
            'paymentAmount.integer' => 'Nominal pembayaran harus berupa angka.',
        ]);

        $sellerId = Auth::id();
        $enteredAmount = (int) $this->paymentAmount;
        $expectedTotal = Auth::user()->unsettledAdminFeeTotal();

        if ($expectedTotal <= 0) {
            $this->addError('paymentAmount', 'Tidak ada tagihan biaya admin yang perlu dibayar saat ini.');

            return;
        }

        if ($enteredAmount !== $expectedTotal) {
            $this->addError(
                'paymentAmount',
                'Nominal pembayaran harus tepat sesuai tagihan sebesar Rp '.number_format($expectedTotal, 0, ',', '.').'.'
            );

            return;
        }

        $settlement = DB::transaction(function () use ($sellerId): ?SellerFeeSettlement {
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
            $now = now();

            $settlement = SellerFeeSettlement::create([
                'settlement_number' => SellerFeeSettlement::generateSettlementNumber(),
                'seller_id' => $sellerId,
                'amount' => $totalAmount,
                'orders_count' => $unsettledOrders->count(),
                'status' => SellerSettlementStatus::Verified,
                'payment_proof_path' => null,
                'payment_note' => 'Pembayaran langsung ke akun Admin',
                'submitted_at' => $now,
                'verified_at' => $now,
            ]);

            Order::query()
                ->whereIn('id', $unsettledOrders->pluck('id'))
                ->update(['fee_settlement_id' => $settlement->id]);

            return $settlement;
        });

        if (! $settlement) {
            $this->addError('paymentAmount', 'Tidak ada tagihan biaya admin yang perlu dibayar.');

            return;
        }

        $this->closePaymentGateway();
        session()->flash(
            'status',
            "Transaksi berhasil! Setoran {$settlement->settlement_number} sebesar Rp ".number_format($settlement->amount, 0, ',', '.').' langsung masuk ke akun Admin dan status setoran Anda telah Lunas.'
        );
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
