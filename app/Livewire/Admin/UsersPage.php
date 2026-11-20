<?php

namespace App\Livewire\Admin;

use App\Enums\UserRole;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kelola Pengguna & Modder')]
class UsersPage extends Component
{
    use WithPagination;

    public string $roleFilter = '';

    #[Validate('required|string|min:2|max:100')]
    public string $newName = '';

    #[Validate('required|email|max:150|unique:users,email')]
    public string $newEmail = '';

    #[Validate('required|string|min:8')]
    public string $newPassword = '';

    #[Validate('required|string|min:2|max:120')]
    public string $newStoreName = '';

    public function createSeller(): void
    {
        $this->validate();

        DB::transaction(function (): void {
            $user = User::create([
                'name' => $this->newName,
                'email' => $this->newEmail,
                'password' => $this->newPassword,
                'role' => UserRole::Seller,
                'is_active' => true,
            ]);

            SellerProfile::create([
                'user_id' => $user->id,
                'store_name' => $this->newStoreName,
                'slug' => Str::slug($this->newStoreName).'-'.$user->id,
                'is_verified' => true,
            ]);
        });

        $this->reset(['newName', 'newEmail', 'newPassword', 'newStoreName']);
        session()->flash('status', 'Client Modder baru berhasil ditambahkan.');
    }

    public function toggleActive(int $userId): void
    {
        if ($userId === Auth::id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun admin yang sedang digunakan.');

            return;
        }

        $user = User::query()->findOrFail($userId);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function promoteToSeller(int $userId): void
    {
        $user = User::query()->findOrFail($userId);
        if ($user->isAdmin()) {
            return;
        }

        DB::transaction(function () use ($user): void {
            $user->update(['role' => UserRole::Seller]);
            SellerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'store_name' => $user->name.' Mod Store',
                    'slug' => Str::slug($user->name).'-'.$user->id,
                    'is_verified' => true,
                ]
            );
        });

        session()->flash('status', "{$user->name} sekarang terdaftar sebagai Client Modder.");
    }

    public function render(): View
    {
        $query = User::query()->with('sellerProfile')->latest();

        if ($this->roleFilter !== '') {
            $query->where('role', $this->roleFilter);
        }

        return view('livewire.admin.users-page', [
            'users' => $query->paginate(12),
        ]);
    }
}
