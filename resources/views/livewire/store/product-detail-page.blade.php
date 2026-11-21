<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <nav aria-label="Breadcrumb" class="mb-6 flex flex-wrap items-center gap-2 text-xs font-medium text-[#A0AAA2]">
        <a href="{{ route('home') }}" class="hover:text-[#7CFF00]">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog') }}" class="hover:text-[#7CFF00]">Katalog</a>
        @if ($product->game)
            <span>/</span>
            <a href="{{ route('catalog', ['game' => $product->game->slug]) }}" class="hover:text-[#7CFF00]">{{ $product->game->name }}</a>
        @endif
        <span>/</span>
        <span class="font-bold text-[#F2F5F0]">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-8">
            <div class="overflow-hidden rounded-2xl border border-[#29342D] bg-[#101614]">
                <div class="aspect-[16/9] w-full bg-[#151D19]">
                    @if ($product->thumbnail_path)
                        <img
                            src="{{ asset('storage/'.$product->thumbnail_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <img
                            src="{{ $product->game?->coverUrl() ?? asset('assets/game-covers/open-world.svg') }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @endif
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-lg border border-[#7CFF00]/40 bg-[#151D19] px-2.5 py-1 text-xs font-bold text-[#7CFF00]">
                            {{ $product->game?->short_code }}
                        </span>
                        <span class="rounded-lg border border-[#29342D] bg-[#151D19] px-2.5 py-1 text-xs font-semibold text-[#A0AAA2]">
                            {{ $product->category?->name }}
                        </span>
                        <x-status-badge :status="$product->pricing_type" />
                        @if (! $product->isActive())
                            <x-status-badge :status="$product->status" />
                        @endif
                    </div>

                    <h1 class="font-display mt-4 text-2xl font-extrabold text-[#F2F5F0] sm:text-3xl">{{ $product->name }}</h1>
                    <p class="mt-2 text-xs text-[#A0AAA2]">
                        Oleh <span class="font-bold text-[#F2F5F0]">{{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}</span>
                        &middot; Versi Mod <span class="font-bold text-[#F2F5F0]">{{ $product->mod_version }}</span>
                        &middot; {{ number_format($product->downloads_count) }} unduhan
                    </p>

                    <div class="mt-6 border-t border-[#29342D] pt-6">
                        <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Deskripsi Lengkap</h2>
                        <div class="mt-3 whitespace-pre-line text-sm leading-relaxed text-[#A0AAA2]">{{ $product->description }}</div>
                    </div>

                    @if ($product->install_guide)
                        <div class="mt-6 border-t border-[#29342D] pt-6">
                            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Panduan Instalasi</h2>
                            <div class="mt-3 whitespace-pre-line rounded-xl border border-[#29342D] bg-[#151D19] p-4 text-sm leading-relaxed text-[#F2F5F0]">{{ $product->install_guide }}</div>
                        </div>
                    @endif

                    @if ($product->changelog)
                        <div class="mt-6 border-t border-[#29342D] pt-6">
                            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Changelog & Riwayat Versi</h2>
                            <div class="mt-3 whitespace-pre-line text-sm leading-relaxed text-[#A0AAA2]">{{ $product->changelog }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-5 lg:col-span-4">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <div>
                    @if ($product->isFree())
                        <p class="text-xs font-bold uppercase tracking-wider text-[#35D07F]">Mod Gratis</p>
                        <p class="font-display mt-1 text-3xl font-extrabold text-[#7CFF00]">Rp 0</p>
                    @else
                        <p class="text-xs font-bold uppercase tracking-wider text-[#B6E879]">Konten Digital Berbayar</p>
                        @if ($product->promo_price && $product->promo_price < $product->price)
                            <p class="mt-1 text-sm text-[#A0AAA2] line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="font-display text-3xl font-extrabold text-[#F2F5F0]">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                        @else
                            <p class="font-display mt-1 text-3xl font-extrabold text-[#F2F5F0]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    @endif
                </div>

                <div class="mt-5 space-y-3">
                    @if ($product->isFree())
                        <a
                            href="{{ route('downloads.free', $product->slug) }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center gap-2 rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B]"
                        >
                            <span>Unduh Gratis Langsung</span>
                        </a>
                    @elseif ($paidDownloadUrl)
                        <a
                            href="{{ $paidDownloadUrl }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center gap-2 rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] transition-colors hover:bg-[#9BFF3B]"
                        >
                            <span>Unduh Mod (Sudah Dibeli)</span>
                        </a>
                    @elseif (! $sellerHasPayments)
                        <div class="rounded-xl border border-[#F5C451]/40 bg-[#F5C451]/15 p-3 text-xs font-medium text-[#F5C451]">
                            Modder belum mengaktifkan metode pembayaran untuk produk ini.
                        </div>
                    @elseif ($sellerOverFeeLimit)
                        <div class="rounded-xl border border-[#F5C451]/40 bg-[#F5C451]/15 p-3 text-xs font-medium text-[#F5C451]">
                            Pembelian mod berbayar di toko ini sementara dijeda menunggu penyelesaian administrasi platform.
                        </div>
                    @else
                        <a
                            href="{{ route('checkout', $product->slug) }}"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B]"
                        >
                            Beli Sekarang
                        </a>
                    @endif

                    <button
                        type="button"
                        wire:click="toggleWishlist"
                        class="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-bold text-[#F2F5F0] transition-colors hover:border-[#7CFF00]"
                    >
                        <img src="{{ asset('assets/icons/heart.svg') }}" alt="" class="h-4 w-4">
                        <span>{{ $isWishlisted ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}</span>
                    </button>
                </div>

                <dl class="mt-6 space-y-3 border-t border-[#29342D] pt-5 text-xs">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Game</dt>
                        <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->game?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Versi Game</dt>
                        <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->compatible_game_version }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Versi Mod</dt>
                        <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->mod_version }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Ukuran File</dt>
                        <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->formattedFileSize() }}</dd>
                    </div>
                    @if ($product->dependencies)
                        <div class="flex justify-between gap-3">
                            <dt class="text-[#A0AAA2]">DLC / Dependency</dt>
                            <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->dependencies }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Pembaruan</dt>
                        <dd class="text-right font-bold text-[#F2F5F0]">{{ $product->updated_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            <x-ad-slot placement="sidebar_promo" />
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <section class="mt-12">
            <h2 class="font-display flex items-center gap-2 text-xl font-extrabold text-[#F2F5F0]">
                <span class="text-[#7CFF00]">//</span>
                <span>Mod Lain untuk {{ $product->game?->name }}</span>
            </h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedProducts as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
</div>
