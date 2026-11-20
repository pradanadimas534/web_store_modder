<div class="mx-auto max-w-[1100px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Pengaturan Biaya Admin & Tarif Iklan</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Atur biaya admin transaksi mod berbayar, tarif slot iklan, serta rekening tujuan pembayaran iklan untuk operasional web.</p>
    </div>

    <x-admin-nav />

    <form wire:submit="saveSettings" class="space-y-6">
        <section class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
            <h2 class="text-base font-bold text-[#F8FAFC]">Biaya Admin Transaksi Mod Berbayar</h2>
            <p class="mt-1 text-xs text-[#AAB2C5]">Biaya ini otomatis ditambahkan pada total checkout pembeli dan tercatat sebagai penghasilan operasional platform.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="fee-percent" class="block text-sm font-semibold text-[#F8FAFC]">Persentase Biaya Admin (%)</label>
                    <input id="fee-percent" type="number" step="0.5" min="0" max="50" wire:model="order_admin_fee_percent" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('order_admin_fee_percent') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fee-fixed" class="block text-sm font-semibold text-[#F8FAFC]">Biaya Admin Tetap (Rupiah)</label>
                    <input id="fee-fixed" type="number" min="0" wire:model="order_admin_fee_fixed" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('order_admin_fee_fixed') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fee-limit" class="block text-sm font-semibold text-[#F8FAFC]">Batas Tunggakan Modder (Rp)</label>
                    <input id="fee-limit" type="number" min="0" wire:model="max_unpaid_admin_fee" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    <p class="mt-1 text-xs text-[#AAB2C5]">Isi 0 jika tanpa batas.</p>
                    @error('max_unpaid_admin_fee') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
            <h2 class="text-base font-bold text-[#F8FAFC]">Tarif Slot Iklan & Biaya Admin Periklanan</h2>
            <p class="mt-1 text-xs text-[#AAB2C5]">Tarif tampil otomatis di halaman Pasang Iklan sesuai posisi banner yang dipilih pengiklan.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="ad-catalog" class="block text-sm font-semibold text-[#F8FAFC]">Tarif Banner Katalog (Rp)</label>
                    <input id="ad-catalog" type="number" min="0" wire:model="ad_price_catalog_banner" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('ad_price_catalog_banner') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ad-sidebar" class="block text-sm font-semibold text-[#F8FAFC]">Tarif Kartu Promo Sidebar (Rp)</label>
                    <input id="ad-sidebar" type="number" min="0" wire:model="ad_price_sidebar_promo" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('ad_price_sidebar_promo') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ad-footer" class="block text-sm font-semibold text-[#F8FAFC]">Tarif Strip Promo Footer (Rp)</label>
                    <input id="ad-footer" type="number" min="0" wire:model="ad_price_footer_strip" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('ad_price_footer_strip') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ad-fee" class="block text-sm font-semibold text-[#F8FAFC]">Biaya Admin Iklan (Rp)</label>
                    <input id="ad-fee" type="number" min="0" wire:model="ad_admin_fee_fixed" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('ad_admin_fee_fixed') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
            <h2 class="text-base font-bold text-[#F8FAFC]">Rekening Pembayaran Platform (Admin)</h2>
            <p class="mt-1 text-xs text-[#AAB2C5]">Ditampilkan kepada pengiklan di halaman Pasang Iklan sebagai tujuan transfer.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="admin-provider" class="block text-sm font-semibold text-[#F8FAFC]">Bank / E-Wallet</label>
                    <input id="admin-provider" type="text" wire:model="admin_payment_provider" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('admin_payment_provider') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="admin-number" class="block text-sm font-semibold text-[#F8FAFC]">Nomor Rekening</label>
                    <input id="admin-number" type="text" wire:model="admin_payment_account_number" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('admin_payment_account_number') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="admin-holder" class="block text-sm font-semibold text-[#F8FAFC]">Atas Nama</label>
                    <input id="admin-holder" type="text" wire:model="admin_payment_account_holder" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('admin_payment_account_holder') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="admin-instructions" class="block text-sm font-semibold text-[#F8FAFC]">Instruksi Pembayaran Iklan (Opsional)</label>
                <textarea id="admin-instructions" rows="2" wire:model="admin_payment_instructions" class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3 text-sm text-[#F8FAFC]"></textarea>
                @error('admin_payment_instructions') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
            </div>
        </section>

        <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-[46px] items-center rounded-xl bg-[#7C4DFF] px-6 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] hover:bg-[#6D3DF5] disabled:opacity-60">
            <span wire:loading.remove wire:target="saveSettings">Simpan Pengaturan Biaya</span>
            <span wire:loading wire:target="saveSettings">Menyimpan...</span>
        </button>
    </form>
</div>
