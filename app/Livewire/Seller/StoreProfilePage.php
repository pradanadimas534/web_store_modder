<?php

namespace App\Livewire\Seller;

use App\Models\SellerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Profil Toko Modder')]
class StoreProfilePage extends Component
{
    #[Validate('required|string|min:3|max:120')]
    public string $store_name = '';

    #[Validate('nullable|string|max:600')]
    public string $bio = '';

    #[Validate('nullable|string|max:30')]
    public string $whatsapp = '';

    public function mount(): void
    {
        $profile = Auth::user()->sellerProfile;

        if ($profile) {
            $this->store_name = $profile->store_name;
            $this->bio = (string) ($profile->bio ?? '');
            $this->whatsapp = (string) ($profile->whatsapp ?? '');
        } else {
            $this->store_name = Auth::user()->name;
        }
    }

    public function saveProfile(): void
    {
        $this->validate();

        $user = Auth::user();
        $slug = Str::slug($this->store_name).'-'.$user->id;

        SellerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => $this->store_name,
                'slug' => $slug,
                'bio' => $this->bio !== '' ? $this->bio : null,
                'whatsapp' => $this->whatsapp !== '' ? $this->whatsapp : null,
            ]
        );

        session()->flash('status', 'Profil toko modder berhasil disimpan.');
    }

    public function render(): View
    {
        return view('livewire.seller.store-profile-page');
    }
}
