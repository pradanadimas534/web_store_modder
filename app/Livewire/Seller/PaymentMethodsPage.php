<?php

namespace App\Livewire\Seller;

use App\Models\SellerPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Metode Pembayaran Modder')]
class PaymentMethodsPage extends Component
{
    public ?int $editingId = null;

    #[Validate('required|string|min:2|max:80')]
    public string $provider_name = '';

    #[Validate('required|string|min:4|max:100')]
    public string $account_number = '';

    #[Validate('required|string|min:2|max:120')]
    public string $account_holder = '';

    #[Validate('nullable|string|max:500')]
    public string $instructions = '';

    public bool $is_active = true;

    public function saveMethod(): void
    {
        $this->validate();

        $payload = [
            'provider_name' => $this->provider_name,
            'account_number' => $this->account_number,
            'account_holder' => $this->account_holder,
            'instructions' => $this->instructions !== '' ? $this->instructions : null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId !== null) {
            $method = SellerPaymentMethod::query()->findOrFail($this->editingId);
            Gate::authorize('manage', $method);
            $method->update($payload);
            session()->flash('status', 'Metode pembayaran diperbarui.');
        } else {
            SellerPaymentMethod::create([
                ...$payload,
                'user_id' => Auth::id(),
            ]);
            session()->flash('status', 'Metode pembayaran baru ditambahkan.');
        }

        $this->reset(['editingId', 'provider_name', 'account_number', 'account_holder', 'instructions']);
        $this->is_active = true;
    }

    public function editMethod(int $methodId): void
    {
        $method = SellerPaymentMethod::query()->findOrFail($methodId);
        Gate::authorize('manage', $method);

        $this->editingId = $method->id;
        $this->provider_name = $method->provider_name;
        $this->account_number = $method->account_number;
        $this->account_holder = $method->account_holder;
        $this->instructions = (string) ($method->instructions ?? '');
        $this->is_active = $method->is_active;
    }

    public function toggleActive(int $methodId): void
    {
        $method = SellerPaymentMethod::query()->findOrFail($methodId);
        Gate::authorize('manage', $method);

        $method->update(['is_active' => ! $method->is_active]);
    }

    public function deleteMethod(int $methodId): void
    {
        $method = SellerPaymentMethod::query()->findOrFail($methodId);
        Gate::authorize('manage', $method);

        $method->delete();
        session()->flash('status', 'Metode pembayaran dihapus.');
    }

    public function render(): View
    {
        return view('livewire.seller.payment-methods-page', [
            'methods' => SellerPaymentMethod::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }
}
