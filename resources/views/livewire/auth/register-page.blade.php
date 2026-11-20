<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 sm:p-8">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Buat Akun Pembeli</h1>
        <p class="mt-2 text-sm text-[#AAB2C5]">Daftar untuk membeli mod berbayar, menyimpan wishlist, dan mengakses riwayat unduhan.</p>

        <form wire:submit="register" class="mt-6 space-y-4">
            <div>
                <label for="reg-name" class="block text-sm font-semibold text-[#F8FAFC]">Nama Lengkap</label>
                <input
                    id="reg-name"
                    type="text"
                    wire:model="name"
                    autocomplete="name"
                    placeholder="Nama tampilan Anda"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                >
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-email" class="block text-sm font-semibold text-[#F8FAFC]">Email</label>
                <input
                    id="reg-email"
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
                <label for="reg-password" class="block text-sm font-semibold text-[#F8FAFC]">Kata Sandi</label>
                <input
                    id="reg-password"
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reg-password-confirmation" class="block text-sm font-semibold text-[#F8FAFC]">Konfirmasi Kata Sandi</label>
                <input
                    id="reg-password-confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi"
                    required
                    class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                >
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5] disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="register">Daftar Akun</span>
                <span wire:loading wire:target="register">Membuat akun...</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#AAB2C5]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-[#A78BFA] hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>
