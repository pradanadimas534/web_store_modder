<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Masuk')]
class LoginPage extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function authenticate(): void
    {
        $this->validate();

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.");

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey, 60);
            $this->addError('email', 'Email atau kata sandi tidak sesuai.');

            return;
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $this->addError('email', 'Akun Anda sedang dinonaktifkan. Hubungi admin untuk bantuan.');

            return;
        }

        RateLimiter::clear($throttleKey);
        request()->session()->regenerate();

        $this->redirectIntended($user->dashboardRoute());
    }

    public function render(): View
    {
        return view('livewire.auth.login-page');
    }
}
