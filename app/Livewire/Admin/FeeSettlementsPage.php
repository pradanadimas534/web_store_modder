<?php

namespace App\Livewire\Admin;

use App\Enums\SellerSettlementStatus;
use App\Models\Order;
use App\Models\SellerFeeSettlement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Verifikasi Setoran Biaya Admin Modder')]
class FeeSettlementsPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public array $rejectNotes = [];

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function verifySettlement(int $settlementId): void
    {
        $settlement = SellerFeeSettlement::query()->findOrFail($settlementId);

        if ($settlement->status !== SellerSettlementStatus::PendingVerification) {
            return;
        }

        $settlement->update([
            'status' => SellerSettlementStatus::Verified,
            'admin_note' => null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        session()->flash('status', "Setoran {$settlement->settlement_number} terverifikasi lunas.");
    }

    public function rejectSettlement(int $settlementId): void
    {
        $reason = trim((string) ($this->rejectNotes[$settlementId] ?? ''));
        if ($reason === '') {
            $this->addError("rejectNotes.{$settlementId}", 'Isi alasan penolakan setoran terlebih dahulu.');

            return;
        }

        $settlement = SellerFeeSettlement::query()->findOrFail($settlementId);

        if ($settlement->status !== SellerSettlementStatus::PendingVerification) {
            return;
        }

        // Release linked orders so the modder can resubmit with valid transfer proof.
        DB::transaction(function () use ($settlement, $reason): void {
            Order::query()
                ->where('fee_settlement_id', $settlement->id)
                ->update(['fee_settlement_id' => null]);

            $settlement->update([
                'status' => SellerSettlementStatus::Rejected,
                'admin_note' => $reason,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);
        });

        unset($this->rejectNotes[$settlementId]);
        session()->flash('status', "Setoran {$settlement->settlement_number} ditolak dan tagihan dikembalikan ke modder.");
    }

    public function render(): View
    {
        $query = SellerFeeSettlement::query()
            ->with(['seller.sellerProfile', 'verifier'])
            ->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.fee-settlements-page', [
            'settlements' => $query->paginate(10),
        ]);
    }
}
