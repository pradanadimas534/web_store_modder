<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Kelola Mod Saya</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Unggah mod gratis atau berbayar. File arsip disimpan secara privat di server.</p>
        </div>
        @if (! $showForm)
            <button type="button" wire:click="startCreate" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-5 text-sm font-bold text-white hover:bg-[#6D3DF5]">
                Tambah Mod Baru
            </button>
        @endif
    </div>

    <x-seller-nav />

    @if ($showForm)
        <form wire:submit="saveProduct" class="mb-8 rounded-2xl border border-[#30364D] bg-[#121525] p-6">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-bold text-[#F8FAFC]">{{ $editingId ? 'Edit Mod' : 'Unggah Mod Baru' }}</h2>
                <button type="button" wire:click="cancelForm" class="text-sm font-semibold text-[#AAB2C5] hover:text-[#F8FAFC]">Tutup</button>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="mod-name" class="block text-sm font-semibold text-[#F8FAFC]">Nama Mod</label>
                    <input id="mod-name" type="text" wire:model="name" placeholder="Contoh: Map Lintas Sumatera ETS2" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('name') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-game" class="block text-sm font-semibold text-[#F8FAFC]">Game</label>
                    <select id="mod-game" wire:model="game_id" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                        @foreach ($games as $game)
                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                        @endforeach
                    </select>
                    @error('game_id') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-category" class="block text-sm font-semibold text-[#F8FAFC]">Kategori</label>
                    <select id="mod-category" wire:model="category_id" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-pricing" class="block text-sm font-semibold text-[#F8FAFC]">Tipe Mod</label>
                    <select id="mod-pricing" wire:model.live="pricing_type" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                        <option value="free">Gratis (Unduh Langsung)</option>
                        <option value="paid">Berbayar (Perlu Transaksi)</option>
                    </select>
                    @error('pricing_type') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                @if ($pricing_type === 'paid')
                    <div>
                        <label for="mod-price" class="block text-sm font-semibold text-[#F8FAFC]">Harga (Rupiah)</label>
                        <input id="mod-price" type="number" min="1000" wire:model="price" placeholder="50000" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                        @error('price') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mod-promo" class="block text-sm font-semibold text-[#F8FAFC]">Harga Promo (Opsional)</label>
                        <input id="mod-promo" type="number" min="1000" wire:model="promo_price" placeholder="Kosongkan jika tidak ada promo" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                        @error('promo_price') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div>
                    <label for="mod-version" class="block text-sm font-semibold text-[#F8FAFC]">Versi Mod</label>
                    <input id="mod-version" type="text" wire:model="mod_version" placeholder="1.0.0" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('mod_version') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-compat" class="block text-sm font-semibold text-[#F8FAFC]">Versi Game Kompatibel</label>
                    <input id="mod-compat" type="text" wire:model="compatible_game_version" placeholder="Contoh: ETS2 1.51 - 1.53" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('compatible_game_version') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-short" class="block text-sm font-semibold text-[#F8FAFC]">Ringkasan Singkat</label>
                    <input id="mod-short" type="text" wire:model="short_description" placeholder="Ringkasan satu kalimat tentang mod ini" required class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                    @error('short_description') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-desc" class="block text-sm font-semibold text-[#F8FAFC]">Deskripsi Lengkap</label>
                    <textarea id="mod-desc" rows="4" wire:model="description" required class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-sm text-[#F8FAFC]"></textarea>
                    @error('description') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-dep" class="block text-sm font-semibold text-[#F8FAFC]">DLC / Dependency (Opsional)</label>
                    <input id="mod-dep" type="text" wire:model="dependencies" placeholder="Contoh: DLC Going East" class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
                </div>

                <div>
                    <label for="mod-thumb" class="block text-sm font-semibold text-[#F8FAFC]">Thumbnail Preview (Opsional)</label>
                    <input id="mod-thumb" type="file" wire:model="thumbnail" accept="image/png,image/jpeg,image/webp" class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white">
                    @error('thumbnail') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-file" class="block text-sm font-semibold text-[#F8FAFC]">
                        File Arsip Mod (.zip, .scs, .rar, .7z, maks 50 MB) {{ $editingId ? '(Kosongkan jika tidak mengganti file)' : '' }}
                    </label>
                    <input id="mod-file" type="file" wire:model="modFile" class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white">
                    @error('modFile') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-guide" class="block text-sm font-semibold text-[#F8FAFC]">Panduan Instalasi (Opsional)</label>
                    <textarea id="mod-guide" rows="3" wire:model="install_guide" class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-sm text-[#F8FAFC]"></textarea>
                </div>

                <div>
                    <label for="mod-changelog" class="block text-sm font-semibold text-[#F8FAFC]">Changelog (Opsional)</label>
                    <textarea id="mod-changelog" rows="3" wire:model="changelog" class="mt-1.5 w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3.5 text-sm text-[#F8FAFC]"></textarea>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-6 text-sm font-bold text-white hover:bg-[#6D3DF5] disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveProduct">{{ $editingId ? 'Simpan Perubahan' : 'Unggah Mod' }}</span>
                    <span wire:loading wire:target="saveProduct">Menyimpan mod...</span>
                </button>
                <button type="button" wire:click="cancelForm" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] px-5 text-sm font-semibold text-[#AAB2C5] hover:text-[#F8FAFC]">
                    Batal
                </button>
            </div>
        </form>
    @endif

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Belum ada mod di toko Anda</h2>
            <p class="mt-2 text-sm text-[#AAB2C5]">Tambahkan mod pertama Anda untuk mulai membagikan mod gratis atau menjual mod berbayar.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($products as $product)
                <article class="flex flex-col justify-between gap-4 rounded-2xl border border-[#30364D] bg-[#121525] p-5 sm:flex-row sm:items-center">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-status-badge :status="$product->status" />
                            <x-status-badge :status="$product->pricing_type" />
                            <span class="text-xs text-[#AAB2C5]">{{ $product->game?->short_code }} &middot; v{{ $product->mod_version }}</span>
                        </div>
                        <h3 class="mt-2 text-base font-bold text-[#F8FAFC]">{{ $product->name }}</h3>
                        <p class="mt-1 text-xs text-[#AAB2C5]">
                            {{ $product->isFree() ? 'Gratis' : 'Rp '.number_format($product->effectivePrice(), 0, ',', '.') }}
                            &middot; {{ $product->downloads_count }} unduhan
                        </p>
                        @if ($product->rejection_reason)
                            <p class="mt-2 text-xs text-[#F87171]">Alasan penolakan: {{ $product->rejection_reason }}</p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-xs font-semibold text-[#F8FAFC]">
                            Pratinjau
                        </a>
                        <button type="button" wire:click="startEdit({{ $product->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#7C4DFF]/60 bg-[#1A1E30] px-4 text-xs font-semibold text-[#A78BFA]">
                            Edit
                        </button>
                        <button type="button" wire:click="deleteProduct({{ $product->id }})" wire:confirm="Hapus mod ini dari toko?" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#F87171]/40 px-4 text-xs font-semibold text-[#F87171]">
                            Hapus
                        </button>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
