<div class="mx-auto max-w-[1320px] px-4 py-8 lg:px-8">
    <div class="flex flex-col justify-between gap-4 border-b border-[#30364D] pb-6 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC] sm:text-3xl">Katalog Mod</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Cari mod gratis atau berbayar berdasarkan game, kategori, dan versi kompatibilitas.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <label for="catalog-sort" class="text-xs font-semibold text-[#AAB2C5]">Urutkan</label>
            <select
                id="catalog-sort"
                wire:model.live="sort"
                class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] focus:border-[#7C4DFF]"
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
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-[#F8FAFC]">Filter Katalog</h2>
                    @if ($search !== '' || $game !== '' || $category !== '' || $pricing !== '')
                        <button type="button" wire:click="resetFilters" class="text-xs font-semibold text-[#A78BFA] hover:underline">
                            Reset
                        </button>
                    @endif
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="filter-search" class="block text-xs font-semibold text-[#AAB2C5]">Pencarian</label>
                        <input
                            id="filter-search"
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nama mod atau versi game..."
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                        >
                    </div>

                    <div>
                        <label for="filter-pricing" class="block text-xs font-semibold text-[#AAB2C5]">Tipe Harga</label>
                        <select
                            id="filter-pricing"
                            wire:model.live="pricing"
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] focus:border-[#7C4DFF]"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="free">Gratis (Unduh Langsung)</option>
                            <option value="paid">Berbayar</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-game" class="block text-xs font-semibold text-[#AAB2C5]">Game</label>
                        <select
                            id="filter-game"
                            wire:model.live="game"
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] focus:border-[#7C4DFF]"
                        >
                            <option value="">Semua Game</option>
                            @foreach ($games as $item)
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter-category" class="block text-xs font-semibold text-[#AAB2C5]">Kategori Mod</label>
                        <select
                            id="filter-category"
                            wire:model.live="category"
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] focus:border-[#7C4DFF]"
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
            <div wire:loading class="mb-4 rounded-xl border border-[#30364D] bg-[#121525] px-4 py-3 text-sm text-[#AAB2C5]">
                Memuat daftar mod sesuai filter...
            </div>

            @if ($products->isEmpty())
                <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
                    <h2 class="text-lg font-bold text-[#F8FAFC]">Tidak ada mod yang cocok</h2>
                    <p class="mt-2 text-sm text-[#AAB2C5]">Coba ubah kata kunci pencarian atau reset filter untuk melihat semua mod aktif.</p>
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="mt-5 inline-flex min-h-[44px] items-center justify-center rounded-xl bg-[#7C4DFF] px-5 text-sm font-semibold text-white hover:bg-[#6D3DF5]"
                    >
                        Reset Semua Filter
                    </button>
                </div>
            @else
                <div class="mb-4 text-sm text-[#AAB2C5]">
                    Menampilkan <span class="font-semibold text-[#F8FAFC]">{{ $products->total() }}</span> mod aktif.
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
