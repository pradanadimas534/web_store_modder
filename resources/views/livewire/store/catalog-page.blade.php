<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="flex flex-col justify-between gap-4 border-b border-[#29342D] pb-6 sm:flex-row sm:items-end">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0] sm:text-3xl">Katalog Produk</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Temukan mod gratis maupun berbayar lintas game, kategori, dan versi kompatibilitas.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <label for="catalog-sort" class="text-xs font-semibold text-[#A0AAA2]">Urutkan</label>
            <select
                id="catalog-sort"
                wire:model.live="sort"
                class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0] focus:border-[#7CFF00]"
            >
                <option value="latest">Terbaru</option>
                <option value="popular">Paling Banyak Diunduh</option>
                <option value="price_asc">Harga Terendah</option>
                <option value="price_desc">Harga Tertinggi</option>
            </select>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <aside class="space-y-5 lg:col-span-3">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-base font-bold text-[#F2F5F0]">Filter Produk</h2>
                    @if ($search !== '' || $game !== '' || $category !== '' || $pricing !== '')
                        <button type="button" wire:click="resetFilters" class="text-xs font-bold text-[#7CFF00] hover:underline">
                            Reset
                        </button>
                    @endif
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="filter-search" class="block text-xs font-semibold text-[#A0AAA2]">Pencarian</label>
                        <input
                            id="filter-search"
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nama mod atau versi game..."
                            class="mt-1.5 min-h-[42px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                        >
                    </div>

                    <div>
                        <label for="filter-pricing" class="block text-xs font-semibold text-[#A0AAA2]">Tipe Harga</label>
                        <select
                            id="filter-pricing"
                            wire:model.live="pricing"
                            class="mt-1.5 min-h-[42px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] focus:border-[#7CFF00]"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="free">Gratis (Unduh Langsung)</option>
                            <option value="paid">Berbayar</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-game" class="block text-xs font-semibold text-[#A0AAA2]">Game</label>
                        <select
                            id="filter-game"
                            wire:model.live="game"
                            class="mt-1.5 min-h-[42px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] focus:border-[#7CFF00]"
                        >
                            <option value="">Semua Game</option>
                            @foreach ($games as $item)
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-category" class="block text-xs font-semibold text-[#A0AAA2]">Kategori Konten</label>
                        <select
                            id="filter-category"
                            wire:model.live="category"
                            class="mt-1.5 min-h-[42px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] focus:border-[#7CFF00]"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <x-ad-slot placement="sidebar_promo" />
        </aside>

        <div class="lg:col-span-9">
            <div wire:loading class="mb-4 rounded-xl border border-[#29342D] bg-[#101614] px-4 py-3 text-sm text-[#A0AAA2]">
                Memuat daftar mod sesuai filter...
            </div>

            @if ($products->isEmpty())
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
                    <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Tidak ada mod yang cocok</h2>
                    <p class="mt-2 text-sm text-[#A0AAA2]">Coba ubah kata kunci pencarian atau reset filter untuk melihat semua mod aktif.</p>
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="mt-5 inline-flex min-h-[42px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]"
                    >
                        Reset Semua Filter
                    </button>
                </div>
            @else
                <div class="mb-4 text-xs font-medium text-[#A0AAA2]">
                    Menampilkan <span class="font-bold text-[#F2F5F0]">{{ $products->total() }}</span> produk aktif.
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif

            <div class="mt-8">
                <x-ad-slot placement="catalog_banner" />
            </div>
        </div>
    </div>
</div>
