<div class="mx-auto max-w-[900px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Profil Toko Modder</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Informasi toko ini ditampilkan di halaman detail produk dan transaksi pembeli.</p>
    </div>

    <x-seller-nav />

    <form wire:submit="saveProfile" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 space-y-4">
        <div>
            <label for="store-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Toko</label>
            <input id="store-name" type="text" wire:model="store_name" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
            @error('store_name') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="store-bio" class="block text-xs font-semibold text-[#F2F5F0]">Bio Toko</label>
            <textarea id="store-bio" rows="3" wire:model="bio" placeholder="Deskripsi singkat karya mod Anda" class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3.5 text-sm text-[#F2F5F0]"></textarea>
            @error('bio') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="store-wa" class="block text-xs font-semibold text-[#F2F5F0]">Kontak WhatsApp (Opsional)</label>
            <input id="store-wa" type="text" wire:model="whatsapp" placeholder="08xxxxxxxxxx" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
            @error('whatsapp') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="inline-flex min-h-[44px] items-center rounded-[10px] bg-[#7CFF00] px-6 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
            Simpan Profil Toko
        </button>
    </form>
</div>
