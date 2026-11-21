<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 sm:p-8">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Masuk ke ModGame.id</h1>
        <p class="mt-2 text-sm text-[#A0AAA2]">Akses unduhan mod, kelola toko modder, atau pantau transaksi Anda.</p>

        <form wire:submit="authenticate" class="mt-6 space-y-4">
            <div>
                <label for="login-email" class="block text-xs font-semibold text-[#F2F5F0]">Email</label>
                <input
                    id="login-email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="email@example.com"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="login-password" class="block text-xs font-semibold text-[#F2F5F0]">Kata Sandi</label>
                <input
                    id="login-password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex min-h-[44px] cursor-pointer items-center gap-2.5 text-xs text-[#A0AAA2]">
                <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-[#29342D] bg-[#151D19] text-[#7CFF00]">
                <span>Ingat sesi saya</span>
            </label>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B] disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="authenticate">Masuk</span>
                <span wire:loading wire:target="authenticate">Memeriksa akun...</span>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-[#A0AAA2]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-[#7CFF00] hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
