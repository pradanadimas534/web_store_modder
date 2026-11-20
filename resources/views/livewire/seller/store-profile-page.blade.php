<div class="mx-auto max-w-[900px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Profil Toko Modder</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Informasi toko ini ditampilkan di halaman detail produk dan transaksi pembeli.</p>
    </div>

    <x-seller-nav />

    <form wire:submit="saveProfile" class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 space-y-4">
        <div>
            <label for="store-name" class="block text-sm font-semibold text-[#F8FAFC]">Nama Toko</label>
            <input id="store-name" type="text" wire:model="store_name" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            @error('store_name') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="store-bio" class="block text-sm font-semibold text-[#F8FAFC]">Bio Toko</label>
            <textarea id="store-bio" rows="3" wire:model="bio" placeholder="Deskripsi singkat karya mod Anda" class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-sm text-[#F8FAFC]"></textarea>
            @error('bio') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="store-wa" class="block text-sm font-semibold text-[#F8FAFC]">Kontak WhatsApp (Opsional)</label>
            <input id="store-wa" type="text" wire:model="whatsapp" placeholder="08xxxxxxxxxx" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            @error('whatsapp') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-6 text-sm font-bold text-white hover:bg-[#6D3DF5]">
            Simpan Profil Toko
        </button>
    </form>
</div>
