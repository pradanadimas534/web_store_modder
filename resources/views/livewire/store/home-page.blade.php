<div class="mx-auto max-w-[1360px] px-4 py-6 sm:py-8 lg:px-8">
    <section
        class="relative overflow-hidden rounded-2xl border border-[#29342D] bg-[#101614] p-6 sm:p-10"
        style="background-image: url('{{ asset('assets/backgrounds/neon-streaks.svg') }}'), url('{{ asset('assets/backgrounds/grid-texture.svg') }}'); background-size: cover, 320px; background-position: center;"
    >
        <div class="relative z-10 grid items-center gap-8 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <h1 class="font-display text-4xl font-extrabold leading-[1.08] tracking-tight text-[#F2F5F0] sm:text-5xl lg:text-[56px]">
                    Semua Game,<br>
                    <span class="text-[#7CFF00]">Satu Tempat.</span>
                </h1>

                <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#A0AAA2] sm:text-base">
                    Temukan dan beli berbagai mod, addon, map, skin, resource pack, script, dan konten eksklusif untuk game favoritmu.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a
                        href="{{ route('catalog') }}"
                        class="inline-flex min-h-[46px] items-center justify-center gap-2 rounded-[10px] bg-[#7CFF00] px-6 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.25)] transition-colors hover:bg-[#9BFF3B]"
                    >
                        <span>Jelajahi Katalog</span>
                    </a>
                    <a
                        href="#game-populer"
                        class="inline-flex min-h-[46px] items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19]/90 px-6 text-sm font-bold text-[#F2F5F0] transition-colors hover:border-[#7CFF00]"
                    >
                        Game Populer
                    </a>
                </div>

                <div class="mt-7 flex flex-wrap items-center gap-5 border-t border-[#29342D]/80 pt-5 text-xs font-semibold text-[#A0AAA2]">
                    <span class="inline-flex items-center gap-2">
                        <img src="{{ asset('assets/icons/shield.svg') }}" alt="" class="h-4 w-4">
                        <span>Aman & Terverifikasi</span>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <img src="{{ asset('assets/icons/bolt.svg') }}" alt="" class="h-4 w-4">
                        <span>Download Setelah Pembayaran</span>
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <img src="{{ asset('assets/icons/headset.svg') }}" alt="" class="h-4 w-4">
                        <span>Support Cepat</span>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:col-span-6">
                @foreach ($games->take(4) as $heroGame)
                    <a
                        href="{{ route('catalog', ['game' => $heroGame->slug]) }}"
                        class="group relative aspect-[4/5] overflow-hidden rounded-2xl border border-[#29342D] bg-[#151D19] transition-all duration-200 hover:-translate-y-1 hover:border-[#7CFF00]"
                    >
                        <img
                            src="{{ $heroGame->coverUrl() }}"
                            alt="{{ $heroGame->name }}"
                            class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105"
                        >
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#080B0A] via-[#080B0A]/80 to-transparent p-3">
                            <span class="font-display block text-xs font-extrabold uppercase tracking-wider text-[#7CFF00]">{{ $heroGame->short_code }}</span>
                            <span class="block truncate text-xs font-bold text-[#F2F5F0]">{{ $heroGame->name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section aria-label="Kategori Game" class="mt-6">
        <div class="flex gap-2.5 overflow-x-auto pb-2">
            <a
                href="{{ route('catalog') }}"
                class="flex min-h-[82px] min-w-[96px] shrink-0 flex-col items-center justify-center gap-2 rounded-2xl border-2 border-[#7CFF00] bg-[#151D19] px-4 py-3 text-center transition-colors hover:bg-[#1C2721]"
            >
                <img src="{{ asset('assets/icons/grid.svg') }}" alt="" class="h-6 w-6">
                <span class="text-xs font-extrabold text-[#F2F5F0]">Semua Game</span>
            </a>

            @foreach ($games as $game)
                <a
                    href="{{ route('catalog', ['game' => $game->slug]) }}"
                    class="flex min-h-[82px] min-w-[104px] shrink-0 flex-col items-center justify-center gap-1.5 rounded-2xl border border-[#29342D] bg-[#101614] px-4 py-3 text-center transition-colors hover:border-[#7CFF00] hover:bg-[#151D19]"
                >
                    <span class="font-display text-xs font-extrabold text-[#7CFF00]">{{ $game->short_code }}</span>
                    <span class="line-clamp-1 text-xs font-semibold text-[#F2F5F0]">{{ $game->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <div class="mt-8 grid gap-6 lg:grid-cols-12">
        <div class="space-y-10 lg:col-span-9">
            <section aria-labelledby="featured-mods-heading">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 id="featured-mods-heading" class="font-display flex items-center gap-2 text-2xl font-extrabold text-[#F2F5F0]">
                            <span class="text-[#7CFF00]">//</span>
                            <span>Produk Terbaru</span>
                        </h2>
                        <p class="mt-1 text-xs text-[#A0AAA2]">Mod dan konten terbaru dari berbagai game pilihan.</p>
                    </div>
                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-[#7CFF00] hover:underline">Lihat Semua</a>
                </div>

                @if ($featuredProducts->isEmpty())
                    <div class="mt-4 rounded-2xl border border-[#29342D] bg-[#101614] p-8 text-center">
                        <p class="text-base font-bold text-[#F2F5F0]">Belum ada mod aktif di katalog.</p>
                        <p class="mt-1 text-xs text-[#A0AAA2]">Mod yang telah disetujui admin akan tampil di halaman ini.</p>
                    </div>
                @else
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($featuredProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @endif
            </section>

            <x-ad-slot placement="catalog_banner" />

            @if ($freeProducts->isNotEmpty())
                <section aria-labelledby="free-mods-heading">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <h2 id="free-mods-heading" class="font-display flex items-center gap-2 text-2xl font-extrabold text-[#F2F5F0]">
                                <span class="text-[#7CFF00]">//</span>
                                <span>Mod Gratis Pilihan</span>
                            </h2>
                            <p class="mt-1 text-xs text-[#A0AAA2]">Unduh langsung tanpa proses checkout atau pembayaran.</p>
                        </div>
                        <a href="{{ route('catalog', ['pricing' => 'free']) }}" class="text-xs font-bold text-[#7CFF00] hover:underline">Semua Mod Gratis</a>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($freeProducts as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </section>
            @endif

            <section id="game-populer" aria-labelledby="popular-games-heading">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <h2 id="popular-games-heading" class="font-display flex items-center gap-2 text-2xl font-extrabold text-[#F2F5F0]">
                            <span class="text-[#7CFF00]">//</span>
                            <span>Game Populer</span>
                        </h2>
                        <p class="mt-1 text-xs text-[#A0AAA2]">Game dengan koleksi mod dan add-on terlengkap di ModGame.id.</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
                    @foreach ($games->take(5) as $game)
                        <a
                            href="{{ route('catalog', ['game' => $game->slug]) }}"
                            class="group relative aspect-[16/10] overflow-hidden rounded-2xl border border-[#29342D] bg-[#151D19] transition-colors hover:border-[#7CFF00]"
                        >
                            <img src="{{ $game->coverUrl() }}" alt="{{ $game->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-105">
                            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-[#080B0A] via-[#080B0A]/60 to-transparent p-3">
                                <span class="font-display text-xs font-extrabold text-[#F2F5F0]">{{ $game->name }}</span>
                            </div>
                        </a>
                    @endforeach

                    <a
                        href="{{ route('catalog') }}"
                        class="flex aspect-[16/10] flex-col items-center justify-center gap-2 rounded-2xl border border-[#29342D] bg-[#101614] p-3 text-center transition-colors hover:border-[#7CFF00] hover:bg-[#151D19]"
                    >
                        <img src="{{ asset('assets/icons/grid.svg') }}" alt="" class="h-6 w-6">
                        <span class="text-xs font-bold text-[#F2F5F0]">Lihat Semua Game</span>
                    </a>
                </div>
            </section>
        </div>

        <aside class="space-y-5 lg:col-span-3">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                <h2 class="font-display text-base font-extrabold text-[#F2F5F0]">Kenapa ModGame.id?</h2>
                <ul class="mt-4 space-y-3 text-xs font-medium text-[#F2F5F0]">
                    <li class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/icons/shield.svg') }}" alt="" class="h-4 w-4 shrink-0">
                        <span>Produk & Kompatibilitas Terverifikasi</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/icons/bolt.svg') }}" alt="" class="h-4 w-4 shrink-0">
                        <span>Download Setelah Pembayaran Sah</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/icons/download.svg') }}" alt="" class="h-4 w-4 shrink-0">
                        <span>Mod Gratis Bisa Unduh Langsung</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/icons/headset.svg') }}" alt="" class="h-4 w-4 shrink-0">
                        <span>Transaksi Langsung ke Modder</span>
                    </li>
                </ul>
            </div>

            <div class="rounded-2xl border border-[#7CFF00]/40 bg-[#101614] p-5">
                <span class="rounded-md border border-[#7CFF00]/40 bg-[#151D19] px-2 py-0.5 text-[11px] font-bold text-[#7CFF00]">Bundle Pack</span>
                <h2 class="font-display mt-2.5 text-base font-extrabold text-[#F2F5F0]">Bundle Game Pack</h2>
                <p class="mt-1 text-xs leading-relaxed text-[#A0AAA2]">Koleksi paket mod, map, dan skin lengkap dengan harga lebih hemat.</p>
                <a
                    href="{{ route('catalog', ['category' => 'bundle']) }}"
                    class="mt-4 inline-flex min-h-[40px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] transition-colors hover:bg-[#9BFF3B]"
                >
                    Lihat Bundle
                </a>
            </div>

            <x-ad-slot placement="sidebar_promo" />
        </aside>
    </div>
</div>
