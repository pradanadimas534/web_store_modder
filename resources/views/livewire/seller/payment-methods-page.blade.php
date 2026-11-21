<div class="mx-auto max-w-[1140px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Pengaturan Pembayaran Modder</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Atur rekening bank, e-wallet (DANA, OVO, GoPay), atau instruksi QRIS agar pembeli dapat membayar mod berbayar langsung ke Anda.</p>
    </div>

    <x-seller-nav />

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <form wire:submit="saveMethod" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">{{ $editingId ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}</h2>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="pay-provider" class="block text-xs font-semibold text-[#F2F5F0]">Bank / E-Wallet / Penyedia</label>
                        <input id="pay-provider" type="text" wire:model="provider_name" placeholder="Contoh: BCA, BRI, DANA, GoPay, QRIS" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('provider_name') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pay-number" class="block text-xs font-semibold text-[#F2F5F0]">Nomor Rekening / Nomor HP</label>
                        <input id="pay-number" type="text" wire:model="account_number" placeholder="Masukkan nomor tujuan pembayaran" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('account_number') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pay-holder" class="block text-xs font-semibold text-[#F2F5F0]">Atas Nama</label>
                        <input id="pay-holder" type="text" wire:model="account_holder" placeholder="Nama pemilik rekening" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('account_holder') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="pay-instructions" class="block text-xs font-semibold text-[#F2F5F0]">Instruksi Pembeli (Opsional)</label>
                        <textarea id="pay-instructions" rows="3" wire:model="instructions" placeholder="Contoh: Sertakan nomor pesanan pada berita transfer" class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3 text-sm text-[#F2F5F0]"></textarea>
                        @error('instructions') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <label class="inline-flex min-h-[44px] cursor-pointer items-center gap-2.5 text-xs text-[#F2F5F0]">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 rounded border-[#29342D] bg-[#151D19] accent-[#7CFF00]">
                        <span>Aktifkan metode ini di halaman checkout</span>
                    </label>

                    <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                        {{ $editingId ? 'Simpan Perubahan' : 'Simpan Metode Pembayaran' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-7">
            @if ($methods->isEmpty())
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-8 text-center">
                    <p class="font-display text-base font-bold text-[#F2F5F0]">Belum ada metode pembayaran</p>
                    <p class="mt-1 text-sm text-[#A0AAA2]">Tambahkan minimal satu rekening atau e-wallet agar pembeli bisa menyelesaikan transaksi mod berbayar.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($methods as $method)
                        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-display text-base font-bold text-[#F2F5F0]">{{ $method->provider_name }}</h3>
                                        <x-status-badge :status="$method->is_active ? 'active' : 'disabled'" :label="$method->is_active ? 'Aktif' : 'Nonaktif'" />
                                    </div>
                                    <p class="mt-1 text-sm font-extrabold text-[#7CFF00]">{{ $method->account_number }}</p>
                                    <p class="text-xs text-[#A0AAA2]">a.n. {{ $method->account_holder }}</p>
                                    @if ($method->instructions)
                                        <p class="mt-2 text-xs text-[#A0AAA2]">{{ $method->instructions }}</p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="editMethod({{ $method->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="toggleActive({{ $method->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#7CFF00]/50 px-3.5 text-xs font-bold text-[#7CFF00]">
                                        {{ $method->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    <button type="button" wire:click="deleteMethod({{ $method->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#FF6262]/40 px-3.5 text-xs font-semibold text-[#FF6262]">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
