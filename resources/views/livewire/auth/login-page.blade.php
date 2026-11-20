<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 sm:p-8">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Masuk ke ModGame.id</h1>
        <p class="mt-2 text-sm text-[#AAB2C5]">Akses unduhan mod, kelola toko modder, atau pantau transaksi Anda.</p>

        <form wire:submit="authenticate" class="mt-6 space-y-4">
            <div>
                <label for="login-email" class="block text-sm font-semibold text-[#F8FAFC]">Email</label>
                <input
                    id="login-email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    placeholder="email@example.com"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="login-password" class="block text-sm font-semibold text-[#F8FAFC]">Kata Sandi</label>
                <input
                    id="login-password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex min-h-[44px] cursor-pointer items-center gap-2.5 text-sm text-[#AAB2C5]">
                <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-[#30364D] bg-[#1A1E30] text-[#7C4DFF]">
                <span>Ingat sesi saya</span>
            </label>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5] disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="authenticate">Masuk</span>
                <span wire:loading wire:target="authenticate">Memeriksa akun...</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#AAB2C5]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-[#A78BFA] hover:underline">Daftar sekarang</a>
        </p>
    </div>
</div>
