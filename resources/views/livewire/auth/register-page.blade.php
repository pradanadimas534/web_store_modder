<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 sm:p-8">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Buat Akun Pembeli</h1>
        <p class="mt-2 text-sm text-[#A0AAA2]">Daftar untuk membeli mod berbayar, menyimpan wishlist, dan mengakses riwayat unduhan.</p>

        <form wire:submit="register" class="mt-6 space-y-4">
            <div>
                <label for="reg-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Lengkap</label>
                <input
                    id="reg-name"
                    type="text"
                    wire:model="name"
                    autocomplete="name"
                    placeholder="Nama tampilan Anda"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                >
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-email" class="block text-xs font-semibold text-[#F2F5F0]">Email</label>
                <input
                    id="reg-email"
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
                <label for="reg-password" class="block text-xs font-semibold text-[#F2F5F0]">Kata Sandi</label>
                <input
                    id="reg-password"
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password-confirmation" class="block text-xs font-semibold text-[#F2F5F0]">Konfirmasi Kata Sandi</label>
                <input
                    id="reg-password-confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                >
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B] disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="register">Daftar Akun</span>
                <span wire:loading wire:target="register">Membuat akun...</span>
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-[#A0AAA2]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-[#7CFF00] hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
