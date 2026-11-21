<?php

namespace App\Livewire\Store;

use App\Enums\AdPaymentStatus;
use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use App\Models\Ad;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Pasang Iklan')]
class SubmitAdPage extends Component
{
    use WithFileUploads;

    #[Validate('required|string|min:2|max:120')]
    public string $advertiser_name = '';

    #[Validate('required|email|max:150')]
    public string $advertiser_email = '';

    #[Validate('required|string|min:5|max:100')]
    public string $title = '';

    #[Validate('required|string|min:10|max:200')]
    public string $description = '';

    #[Validate('required|string|min:2|max:40')]
    public string $cta_text = 'Kunjungi';

    #[Validate('required|url:http,https|max:500')]
    public string $target_url = '';

    #[Validate('required|in:catalog_banner,sidebar_promo,footer_strip')]
    public string $placement = 'catalog_banner';

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $banner = null;

    public bool $showPaymentGateway = false;

    public int|string $paymentAmount = '';

    public bool $submitted = false;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->advertiser_name = $user->name;
            $this->advertiser_email = $user->email;
        }
    }

    public function openPaymentGateway(): void
    {
        $this->validate();
        $this->resetErrorBag('paymentAmount');
        $this->paymentAmount = '';
        $this->showPaymentGateway = true;
    }

    public function closePaymentGateway(): void
    {
        $this->resetErrorBag('paymentAmount');
        $this->reset(['showPaymentGateway', 'paymentAmount']);
    }

    public function submitAd(): void
    {
        $key = 'ad-submit:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('title', 'Batas pengajuan iklan tercapai. Silakan coba beberapa saat lagi.');

            return;
        }

        $this->validate();

        $placementEnum = AdPlacement::from($this->placement);
        $pricing = PlatformSetting::current()->calculateAdTotal($placementEnum);

        if ((string) $this->paymentAmount === '' || ! is_numeric($this->paymentAmount)) {
            $this->addError('paymentAmount', 'Masukkan nominal pembayaran iklan terlebih dahulu.');

            return;
        }

        if ((int) $this->paymentAmount !== $pricing['total_price']) {
            $this->addError(
                'paymentAmount',
                'Nominal pembayaran harus tepat sesuai total tagihan iklan sebesar Rp '.number_format($pricing['total_price'], 0, ',', '.').'.'
            );

            return;
        }

        $bannerPath = $this->banner ? $this->banner->store('ads', 'public') : null;
        $now = now();

        Ad::create([
            'advertiser_id' => Auth::id(),
            'advertiser_name' => $this->advertiser_name,
            'advertiser_email' => $this->advertiser_email,
            'title' => $this->title,
            'description' => $this->description,
            'cta_text' => $this->cta_text,
            'target_url' => $this->target_url,
            'banner_path' => $bannerPath,
            'placement' => $placementEnum,
            'slot_price' => $pricing['slot_price'],
            'admin_fee' => $pricing['admin_fee'],
            'total_price' => $pricing['total_price'],
            'payment_status' => AdPaymentStatus::Paid,
            'payment_proof_path' => null,
            'paid_at' => $now,
            'verified_at' => $now,
            'status' => AdStatus::Active,
        ]);

        RateLimiter::hit($key, 3600);

        $this->reset(['title', 'description', 'target_url', 'banner', 'showPaymentGateway', 'paymentAmount']);
        $this->cta_text = 'Kunjungi';
        $this->submitted = true;
    }

    public function render(): View
    {
        $setting = PlatformSetting::current();
        $selectedPlacement = AdPlacement::tryFrom($this->placement) ?? AdPlacement::CatalogBanner;
        $pricing = $setting->calculateAdTotal($selectedPlacement);

        return view('livewire.store.submit-ad-page', [
            'placements' => AdPlacement::cases(),
            'setting' => $setting,
            'pricing' => $pricing,
        ]);
    }
}
