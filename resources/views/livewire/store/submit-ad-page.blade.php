<div class="mx-auto max-w-[1100px] px-4 py-8 sm:py-12 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-5">
            <h1 class="text-2xl font-extrabold text-[#F8FAFC] sm:text-3xl">Pasang Iklan di ModGame.id</h1>
            <p class="text-sm leading-relaxed text-[#AAB2C5]">
                Siapa pun dapat mengajukan banner promosi untuk server game, channel modding, komunitas, atau produk terkait. Iklan tampil di slot non-intrusif setelah pembayaran diverifikasi admin.
            </p>

            <div class="space-y-3">
                <div class="rounded-xl border border-[#30364D] bg-[#121525] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-[#F8FAFC]">Banner Katalog (Horizontal)</p>
                        <span class="text-xs font-bold text-[#A78BFA]">Rp {{ number_format($setting->ad_price_catalog_banner, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#AAB2C5]">Tampil rapi di antara seksi beranda dan bagian bawah katalog mod.</p>
                </div>
                <div class="rounded-xl border border-[#30364D] bg-[#121525] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-[#F8FAFC]">Kartu Promo Sidebar</p>
                        <span class="text-xs font-bold text-[#A78BFA]">Rp {{ number_format($setting->ad_price_sidebar_promo, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#AAB2C5]">Tampil di sisi samping halaman katalog dan detail produk.</p>
                </div>
                <div class="rounded-xl border border-[#30364D] bg-[#121525] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-[#F8FAFC]">Strip Promo Footer</p>
                        <span class="text-xs font-bold text-[#A78BFA]">Rp {{ number_format($setting->ad_price_footer_strip, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#AAB2C5]">Tampil konsisten tepat di atas footer pada setiap halaman.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-[#7C4DFF]/50 bg-[#121525] p-5">
                <h2 class="text-sm font-bold text-[#F8FAFC]">Tujuan Pembayaran Iklan (Admin)</h2>
                <dl class="mt-3 space-y-1.5 text-xs">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Bank / E-Wallet</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $setting->admin_payment_provider }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Nomor Rekening</dt>
                        <dd class="font-bold text-[#A78BFA]">{{ $setting->admin_payment_account_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $setting->admin_payment_account_holder }}</dd>
                    </div>
                </dl>
                @if ($setting->admin_payment_instructions)
                    <p class="mt-3 text-xs text-[#AAB2C5]">{{ $setting->admin_payment_instructions }}</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 sm:p-8">
                @if ($submitted)
                    <div role="status" class="mb-6 rounded-xl border border-[#22C55E]/40 bg-[#22C55E]/15 p-4 text-sm text-[#22C55E]">
                        Pengajuan iklan Anda telah diterima dan menunggu verifikasi pembayaran serta peninjauan admin.
                    </div>
                @endif

                <form wire:submit="submitAd" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-name" class="block text-sm font-semibold text-[#F8FAFC]">Nama Pengiklan</label>
                            <input
                                id="ad-name"
                                type="text"
                                wire:model="advertiser_name"
                                placeholder="Nama brand atau komunitas"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                            >
                            @error('advertiser_name') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-email" class="block text-sm font-semibold text-[#F8FAFC]">Email Kontak</label>
                            <input
                                id="ad-email"
                                type="email"
                                wire:model="advertiser_email"
                                placeholder="email@example.com"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                            >
                            @error('advertiser_email') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="ad-title" class="block text-sm font-semibold text-[#F8FAFC]">Judul Banner</label>
                        <input
                            id="ad-title"
                            type="text"
                            wire:model="title"
                            placeholder="Contoh: Server Konvoi ETS2 Nusantara 24 Jam"
                            required
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                        >
                        @error('title') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="ad-desc" class="block text-sm font-semibold text-[#F8FAFC]">Deskripsi Singkat</label>
                        <textarea
                            id="ad-desc"
                            rows="3"
                            wire:model="description"
                            placeholder="Jelaskan promosi secara singkat (maksimal 200 karakter)"
                            required
                            class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                        ></textarea>
                        @error('description') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-url" class="block text-sm font-semibold text-[#F8FAFC]">URL Tujuan</label>
                            <input
                                id="ad-url"
                                type="url"
                                wire:model="target_url"
                                placeholder="https://example.com"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                            >
                            @error('target_url') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-cta" class="block text-sm font-semibold text-[#F8FAFC]">Teks Tombol</label>
                            <input
                                id="ad-cta"
                                type="text"
                                wire:model="cta_text"
                                placeholder="Kunjungi"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                            >
                            @error('cta_text') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-placement" class="block text-sm font-semibold text-[#F8FAFC]">Posisi Slot Iklan</label>
                            <select
                                id="ad-placement"
                                wire:model.live="placement"
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] focus:border-[#7C4DFF]"
                            >
                                @foreach ($placements as $item)
                                    <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                @endforeach
                            </select>
                            @error('placement') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-banner" class="block text-sm font-semibold text-[#F8FAFC]">Gambar Banner (Opsional)</label>
                            <input
                                id="ad-banner"
                                type="file"
                                wire:model="banner"
                                accept="image/png,image/jpeg,image/webp"
                                class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white"
                            >
                            @error('banner') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                        <h3 class="text-sm font-bold text-[#F8FAFC]">Rincian Biaya Iklan</h3>
                        <dl class="mt-2.5 space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <dt class="text-[#AAB2C5]">Tarif Slot</dt>
                                <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($pricing['slot_price'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-[#AAB2C5]">Biaya Admin Platform</dt>
                                <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($pricing['admin_fee'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-[#30364D] pt-2 text-sm font-extrabold">
                                <dt class="text-[#F8FAFC]">Total Bayar Iklan</dt>
                                <dd class="text-[#22C55E]">Rp {{ number_format($pricing['total_price'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <label for="ad-proof" class="block text-sm font-semibold text-[#F8FAFC]">Bukti Pembayaran Iklan (Wajib, JPG/PNG/WEBP, maks 4 MB)</label>
                        <input
                            id="ad-proof"
                            type="file"
                            wire:model="paymentProof"
                            accept="image/png,image/jpeg,image/webp"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white"
                        >
                        @error('paymentProof') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5] disabled:opacity-60"
                    >
                        <span wire:loading.remove wire:target="submitAd">Kirim Pengajuan & Bukti Bayar</span>
                        <span wire:loading wire:target="submitAd">Mengirim pengajuan...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
