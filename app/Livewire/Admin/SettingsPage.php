<?php

namespace App\Livewire\Admin;

use App\Models\PlatformSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pengaturan Biaya & Rekening Platform')]
class SettingsPage extends Component
{
    #[Validate('required|numeric|min:0|max:50')]
    public float $order_admin_fee_percent = 5.0;

    #[Validate('required|integer|min:0|max:1000000')]
    public int $order_admin_fee_fixed = 2000;

    #[Validate('required|integer|min:0|max:100000000')]
    public int $max_unpaid_admin_fee = 50000;

    #[Validate('required|integer|min:0|max:100000000')]
    public int $ad_price_catalog_banner = 50000;

    #[Validate('required|integer|min:0|max:100000000')]
    public int $ad_price_sidebar_promo = 35000;

    #[Validate('required|integer|min:0|max:100000000')]
    public int $ad_price_footer_strip = 25000;

    #[Validate('required|integer|min:0|max:10000000')]
    public int $ad_admin_fee_fixed = 5000;

    #[Validate('required|string|min:2|max:80')]
    public string $admin_payment_provider = 'BCA';

    #[Validate('required|string|min:4|max:100')]
    public string $admin_payment_account_number = '';

    #[Validate('required|string|min:2|max:120')]
    public string $admin_payment_account_holder = '';

    #[Validate('nullable|string|max:500')]
    public string $admin_payment_instructions = '';

    public function mount(): void
    {
        $setting = PlatformSetting::current();

        $this->order_admin_fee_percent = $setting->order_admin_fee_percent;
        $this->order_admin_fee_fixed = $setting->order_admin_fee_fixed;
        $this->max_unpaid_admin_fee = $setting->max_unpaid_admin_fee;
        $this->ad_price_catalog_banner = $setting->ad_price_catalog_banner;
        $this->ad_price_sidebar_promo = $setting->ad_price_sidebar_promo;
        $this->ad_price_footer_strip = $setting->ad_price_footer_strip;
        $this->ad_admin_fee_fixed = $setting->ad_admin_fee_fixed;
        $this->admin_payment_provider = $setting->admin_payment_provider;
        $this->admin_payment_account_number = $setting->admin_payment_account_number;
        $this->admin_payment_account_holder = $setting->admin_payment_account_holder;
        $this->admin_payment_instructions = (string) ($setting->admin_payment_instructions ?? '');
    }

    public function saveSettings(): void
    {
        $this->validate();

        PlatformSetting::current()->update([
            'order_admin_fee_percent' => $this->order_admin_fee_percent,
            'order_admin_fee_fixed' => $this->order_admin_fee_fixed,
            'max_unpaid_admin_fee' => $this->max_unpaid_admin_fee,
            'ad_price_catalog_banner' => $this->ad_price_catalog_banner,
            'ad_price_sidebar_promo' => $this->ad_price_sidebar_promo,
            'ad_price_footer_strip' => $this->ad_price_footer_strip,
            'ad_admin_fee_fixed' => $this->ad_admin_fee_fixed,
            'admin_payment_provider' => $this->admin_payment_provider,
            'admin_payment_account_number' => $this->admin_payment_account_number,
            'admin_payment_account_holder' => $this->admin_payment_account_holder,
            'admin_payment_instructions' => $this->admin_payment_instructions !== '' ? $this->admin_payment_instructions : null,
        ]);

        session()->flash('status', 'Pengaturan biaya admin transaksi dan tarif iklan berhasil disimpan.');
    }

    public function render(): View
    {
        return view('livewire.admin.settings-page');
    }
}
