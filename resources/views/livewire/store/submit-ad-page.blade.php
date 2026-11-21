<div class="mx-auto max-w-[1140px] px-4 py-8 sm:py-12 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-5">
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0] sm:text-3xl">Pasang Iklan di ModGame.id</h1>
            <p class="text-sm leading-relaxed text-[#A0AAA2]">
                Siapa pun dapat memasang banner promosi untuk server game, channel modding, komunitas, atau produk terkait. Pembayaran langsung diarahkan ke akun pembayaran Admin dan iklan langsung aktif setelah pembayaran berhasil.
            </p>

            <div class="space-y-3">
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-display text-sm font-bold text-[#F2F5F0]">Banner Katalog (Horizontal)</p>
                        <span class="text-xs font-extrabold text-[#7CFF00]">Rp {{ number_format($setting->ad_price_catalog_banner, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#A0AAA2]">Tampil rapi di antara seksi beranda dan bagian bawah katalog mod.</p>
                </div>
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-display text-sm font-bold text-[#F2F5F0]">Kartu Promo Sidebar</p>
                        <span class="text-xs font-extrabold text-[#7CFF00]">Rp {{ number_format($setting->ad_price_sidebar_promo, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#A0AAA2]">Tampil di sisi samping halaman katalog dan detail produk.</p>
                </div>
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="font-display text-sm font-bold text-[#F2F5F0]">Strip Promo Footer</p>
                        <span class="text-xs font-extrabold text-[#7CFF00]">Rp {{ number_format($setting->ad_price_footer_strip, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-[#A0AAA2]">Tampil konsisten tepat di atas footer pada setiap halaman.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-[#7CFF00]/40 bg-[#101614] p-5">
                <h2 class="font-display text-sm font-bold text-[#F2F5F0]">Akun Pembayaran Iklan (Admin)</h2>
                <dl class="mt-3 space-y-1.5 text-xs">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Bank / E-Wallet</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_provider }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Nomor Rekening</dt>
                        <dd class="font-extrabold text-[#7CFF00]">{{ $setting->admin_payment_account_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_account_holder }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-7">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 sm:p-8">
                @if ($submitted)
                    <div role="status" class="mb-6 rounded-xl border border-[#35D07F]/40 bg-[#35D07F]/15 p-4 text-sm text-[#35D07F]">
                        Transaksi pembayaran iklan berhasil! Dana telah masuk ke akun Admin, status pembayaran Lunas, dan banner iklan Anda kini langsung tayang.
                    </div>
                @endif

                <form wire:submit="openPaymentGateway" class="space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Pengiklan</label>
                            <input
                                id="ad-name"
                                type="text"
                                wire:model="advertiser_name"
                                placeholder="Nama brand atau komunitas"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                            >
                            @error('advertiser_name') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-email" class="block text-xs font-semibold text-[#F2F5F0]">Email Kontak</label>
                            <input
                                id="ad-email"
                                type="email"
                                wire:model="advertiser_email"
                                placeholder="email@example.com"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                            >
                            @error('advertiser_email') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="ad-title" class="block text-xs font-semibold text-[#F2F5F0]">Judul Banner</label>
                        <input
                            id="ad-title"
                            type="text"
                            wire:model="title"
                            placeholder="Contoh: Server Roleplay & Konvoi Nusantara 24 Jam"
                            required
                            class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                        >
                        @error('title') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="ad-desc" class="block text-xs font-semibold text-[#F2F5F0]">Deskripsi Singkat</label>
                        <textarea
                            id="ad-desc"
                            rows="3"
                            wire:model="description"
                            placeholder="Jelaskan promosi secara singkat (maksimal 200 karakter)"
                            required
                            class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                        ></textarea>
                        @error('description') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-url" class="block text-xs font-semibold text-[#F2F5F0]">URL Tujuan</label>
                            <input
                                id="ad-url"
                                type="url"
                                wire:model="target_url"
                                placeholder="https://example.com"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                            >
                            @error('target_url') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-cta" class="block text-xs font-semibold text-[#F2F5F0]">Teks Tombol</label>
                            <input
                                id="ad-cta"
                                type="text"
                                wire:model="cta_text"
                                placeholder="Kunjungi"
                                required
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                            >
                            @error('cta_text') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="ad-placement" class="block text-xs font-semibold text-[#F2F5F0]">Posisi Slot Iklan</label>
                            <select
                                id="ad-placement"
                                wire:model.live="placement"
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] focus:border-[#7CFF00]"
                            >
                                @foreach ($placements as $item)
                                    <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                @endforeach
                            </select>
                            @error('placement') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ad-banner" class="block text-xs font-semibold text-[#F2F5F0]">Gambar Banner (Opsional)</label>
                            <input
                                id="ad-banner"
                                type="file"
                                wire:model="banner"
                                accept="image/png,image/jpeg,image/webp"
                                class="mt-1.5 block w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 py-2 text-xs text-[#A0AAA2] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7CFF00] file:px-3 file:py-1.5 file:text-xs file:font-extrabold file:text-[#080B0A]"
                            >
                            @error('banner') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                        <h3 class="font-display text-sm font-bold text-[#F2F5F0]">Rincian Biaya Iklan</h3>
                        <dl class="mt-2.5 space-y-1.5 text-xs">
                            <div class="flex justify-between">
                                <dt class="text-[#A0AAA2]">Tarif Slot</dt>
                                <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($pricing['slot_price'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-[#A0AAA2]">Biaya Admin Platform</dt>
                                <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($pricing['admin_fee'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-[#29342D] pt-2 text-sm font-extrabold">
                                <dt class="text-[#F2F5F0]">Total Bayar Iklan</dt>
                                <dd class="text-[#7CFF00]">Rp {{ number_format($pricing['total_price'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>

                    @error('paymentAmount')
                        <p class="text-xs font-semibold text-[#FF6262]">{{ $message }}</p>
                    @enderror

                    <button
                        type="submit"
                        class="inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B]"
                    >
                        Bayar Iklan ke Akun Admin
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if ($showPaymentGateway)
        <div
            style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: rgba(8, 11, 10, 0.85); backdrop-filter: blur(4px); padding: 1rem;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-data
            x-on:keydown.escape.window="$wire.closePaymentGateway()"
            wire:click.self="closePaymentGateway"
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="ad-pay-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="ad-pay-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Pembayaran Iklan ke Akun Admin</h2>
                    <span class="rounded-lg border border-[#7CFF00]/40 bg-[#7CFF00]/15 px-2.5 py-0.5 text-xs font-bold text-[#7CFF00]">Direct Pay</span>
                </div>

                <div class="mt-4 rounded-xl border border-[#29342D] bg-[#151D19] p-4 text-xs space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-[#A0AAA2]">Tujuan Akun</span>
                        <span class="font-bold text-[#F2F5F0]">{{ $setting->admin_payment_provider }} &middot; {{ $setting->admin_payment_account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#A0AAA2]">Penerima</span>
                        <span class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_account_holder }}</span>
                    </div>
                    <div class="flex justify-between border-t border-[#29342D] pt-2 text-sm font-extrabold">
                        <span class="text-[#F2F5F0]">Tagihan Harus Dibayar</span>
                        <span class="font-display text-[#7CFF00]">Rp {{ number_format($pricing['total_price'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit="submitAd" class="mt-4 space-y-4">
                    <div>
                        <label for="ad-pay-amount" class="block text-xs font-semibold text-[#F2F5F0]">Masukkan Nominal Pembayaran (Rp)</label>
                        <input
                            id="ad-pay-amount"
                            type="number"
                            wire:model="paymentAmount"
                            autofocus
                            placeholder="{{ $pricing['total_price'] }}"
                            required
                            class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm font-bold text-[#F2F5F0] placeholder-[#A0AAA2]/50 focus:border-[#7CFF00]"
                        >
                        @error('paymentAmount')
                            <p class="mt-1 text-xs font-semibold text-[#FF6262]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 border-t border-[#29342D] pt-4">
                        <button
                            type="button"
                            wire:click="closePaymentGateway"
                            style="background-color: #1C2721; color: #F2F5F0; border: 1px solid #29342D;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#29342D] bg-[#1C2721] px-4 text-xs font-bold text-[#F2F5F0]"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            style="background-color: #7CFF00; color: #080B0A;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B] disabled:opacity-60"
                        >
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
