<div class="mx-auto max-w-[1320px] px-4 py-8 lg:px-8">
    <nav aria-label="Breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 text-sm text-[#AAB2C5]">
        <a href="{{ route('home') }}" class="hover:text-[#F8FAFC]">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog') }}" class="hover:text-[#F8FAFC]">Katalog</a>
        <span>/</span>
        <span class="font-semibold text-[#F8FAFC]">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-8">
            <div class="overflow-hidden rounded-2xl border border-[#30364D] bg-[#121525]">
                <div class="aspect-[16/9] w-full bg-[#1A1E30]">
                    @if ($product->thumbnail_path)
                        <img
                            src="{{ asset('storage/'.$product->thumbnail_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="flex h-full w-full flex-col items-center justify-center p-6 text-center">
                            <span class="text-sm font-bold tracking-wider text-[#38BDF8]">{{ $product->game?->name }}</span>
                            <p class="mt-2 text-xl font-extrabold text-[#F8FAFC]">{{ $product->name }}</p>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-lg border border-[#30364D] bg-[#1A1E30] px-2.5 py-1 text-xs font-semibold text-[#38BDF8]">
                            {{ $product->game?->short_code }}
                        </span>
                        <span class="rounded-lg border border-[#30364D] bg-[#1A1E30] px-2.5 py-1 text-xs font-semibold text-[#AAB2C5]">
                            {{ $product->category?->name }}
                        </span>
                        <x-status-badge :status="$product->pricing_type" />
                        @if (! $product->isActive())
                            <x-status-badge :status="$product->status" />
                        @endif
                    </div>

                    <h1 class="mt-4 text-2xl font-extrabold text-[#F8FAFC] sm:text-3xl">{{ $product->name }}</h1>
                    <p class="mt-2 text-sm text-[#AAB2C5]">
                        Oleh <span class="font-semibold text-[#F8FAFC]">{{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}</span>
                        &middot; Versi Mod <span class="font-semibold text-[#F8FAFC]">{{ $product->mod_version }}</span>
                        &middot; {{ number_format($product->downloads_count) }} unduhan
                    </p>

                    <div class="mt-6 border-t border-[#30364D] pt-6">
                        <h2 class="text-lg font-bold text-[#F8FAFC]">Deskripsi Mod</h2>
                        <div class="mt-3 whitespace-pre-line text-sm leading-relaxed text-[#AAB2C5]">{{ $product->description }}</div>
                    </div>

                    @if ($product->install_guide)
                        <div class="mt-6 border-t border-[#30364D] pt-6">
                            <h2 class="text-lg font-bold text-[#F8FAFC]">Panduan Instalasi</h2>
                            <div class="mt-3 whitespace-pre-line rounded-xl border border-[#30364D] bg-[#1A1E30] p-4 text-sm leading-relaxed text-[#F8FAFC]">{{ $product->install_guide }}</div>
                        </div>
                    @endif

                    @if ($product->changelog)
                        <div class="mt-6 border-t border-[#30364D] pt-6">
                            <h2 class="text-lg font-bold text-[#F8FAFC]">Catatan Pembaruan (Changelog)</h2>
                            <div class="mt-3 whitespace-pre-line text-sm leading-relaxed text-[#AAB2C5]">{{ $product->changelog }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-5 lg:col-span-4">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <div>
                    @if ($product->isFree())
                        <p class="text-sm font-semibold text-[#22C55E]">Mod Gratis</p>
                        <p class="mt-1 text-3xl font-extrabold text-[#F8FAFC]">Rp 0</p>
                    @else
                        <p class="text-sm font-semibold text-[#FBBF24]">Mod Berbayar</p>
                        @if ($product->promo_price && $product->promo_price < $product->price)
                            <p class="mt-1 text-sm text-[#AAB2C5] line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="text-3xl font-extrabold text-[#F8FAFC]">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                        @else
                            <p class="mt-1 text-3xl font-extrabold text-[#F8FAFC]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    @endif
                </div>

                <div class="mt-5 space-y-3">
                    @if ($product->isFree())
                        <a
                            href="{{ route('downloads.free', $product->slug) }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-sm font-bold text-[#0B0D17] transition-colors hover:bg-[#16A34A]"
                        >
                            Unduh Gratis Langsung
                        </a>
                    @elseif ($paidDownloadUrl)
                        <a
                            href="{{ $paidDownloadUrl }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-sm font-bold text-[#0B0D17] transition-colors hover:bg-[#16A34A]"
                        >
                            Unduh Mod (Sudah Dibeli)
                        </a>
                    @elseif (! $sellerHasPayments)
                        <div class="rounded-xl border border-[#FBBF24]/40 bg-[#FBBF24]/15 p-3 text-xs font-medium text-[#FBBF24]">
                            Modder belum mengaktifkan metode pembayaran untuk produk ini.
                        </div>
                    @elseif ($sellerOverFeeLimit)
                        <div class="rounded-xl border border-[#FBBF24]/40 bg-[#FBBF24]/15 p-3 text-xs font-medium text-[#FBBF24]">
                            Pembelian mod berbayar di toko ini sementara dijeda menunggu penyelesaian administrasi platform.
                        </div>
                    @else
                        <a
                            href="{{ route('checkout', $product->slug) }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5]"
                        >
                            Beli Mod Sekarang
                        </a>
                    @endif

                    <button
                        type="button"
                        wire:click="toggleWishlist"
                        class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-sm font-semibold text-[#F8FAFC] transition-colors hover:border-[#7C4DFF]"
                    >
                        {{ $isWishlisted ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}
                    </button>
                </div>

                <dl class="mt-6 space-y-3 border-t border-[#30364D] pt-5 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Game</dt>
                        <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->game?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Versi Game</dt>
                        <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->compatible_game_version }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Versi Mod</dt>
                        <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->mod_version }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Ukuran File</dt>
                        <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->formattedFileSize() }}</dd>
                    </div>
                    @if ($product->dependencies)
                        <div class="flex justify-between gap-3">
                            <dt class="text-[#AAB2C5]">DLC / Dependency</dt>
                            <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->dependencies }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Pembaruan</dt>
                        <dd class="text-right font-semibold text-[#F8FAFC]">{{ $product->updated_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            <x-ad-slot placement="sidebar_promo" />
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-xl font-bold text-[#F8FAFC]">Mod Lain untuk {{ $product->game?->name }}</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedProducts as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
