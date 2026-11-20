<?php

namespace App\Livewire\Admin;

use App\Enums\AdPaymentStatus;
use App\Enums\AdStatus;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kelola Iklan AdSense')]
class AdsPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public string $paymentStatusFilter = '';

    public array $rejectNotes = [];

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPaymentStatusFilter(): void
    {
        $this->resetPage();
    }

    public function verifyPaymentAndActivate(int $adId): void
    {
        $ad = Ad::query()->findOrFail($adId);
        $ad->update([
            'payment_status' => AdPaymentStatus::Paid,
            'status' => AdStatus::Active,
            'admin_note' => null,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        session()->flash('status', "Pembayaran iklan \"{$ad->title}\" terverifikasi lunas dan iklan kini tayang.");
    }

    public function rejectPayment(int $adId): void
    {
        $reason = trim((string) ($this->rejectNotes[$adId] ?? ''));
        if ($reason === '') {
            $this->addError("rejectNotes.{$adId}", 'Isi alasan penolakan pembayaran terlebih dahulu.');

            return;
        }

        $ad = Ad::query()->findOrFail($adId);
        $ad->update([
            'payment_status' => AdPaymentStatus::Rejected,
            'status' => AdStatus::Rejected,
            'admin_note' => $reason,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        unset($this->rejectNotes[$adId]);
        session()->flash('status', "Pembayaran iklan \"{$ad->title}\" ditolak.");
    }

    public function setStatus(int $adId, string $status): void
    {
        $enum = AdStatus::tryFrom($status);
        if (! $enum) {
            return;
        }

        $ad = Ad::query()->findOrFail($adId);

        if ($enum === AdStatus::Active && $ad->payment_status !== AdPaymentStatus::Paid) {
            session()->flash('error', 'Verifikasi pembayaran iklan terlebih dahulu sebelum mengaktifkan penayangan.');

            return;
        }

        $ad->update(['status' => $enum]);

        session()->flash('status', "Status iklan \"{$ad->title}\" diubah menjadi {$enum->label()}.");
    }

    public function deleteAd(int $adId): void
    {
        Ad::query()->findOrFail($adId)->delete();
        session()->flash('status', 'Iklan berhasil dihapus.');
    }

    public function render(): View
    {
        $query = Ad::query()->with('verifier')->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->paymentStatusFilter !== '') {
            $query->where('payment_status', $this->paymentStatusFilter);
        }

        return view('livewire.admin.ads-page', [
            'ads' => $query->paginate(10),
        ]);
    }
}
