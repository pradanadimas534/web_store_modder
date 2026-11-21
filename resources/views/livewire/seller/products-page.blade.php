<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Kelola Mod Saya</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Unggah mod gratis atau berbayar. File arsip disimpan secara privat di server.</p>
        </div>
        @if (! $showForm)
            <button type="button" wire:click="startCreate" class="inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                Tambah Mod Baru
            </button>
        @endif
    </div>

    <x-seller-nav />

    @if ($showForm)
        <form wire:submit="saveProduct" class="mb-8 rounded-2xl border border-[#29342D] bg-[#101614] p-6">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-display text-lg font-bold text-[#F2F5F0]">{{ $editingId ? 'Edit Mod' : 'Unggah Mod Baru' }}</h2>
                <button type="button" wire:click="cancelForm" class="text-xs font-bold text-[#A0AAA2] hover:text-[#F2F5F0]">Tutup</button>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="mod-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Mod</label>
                    <input id="mod-name" type="text" wire:model="name" placeholder="Contoh: Realistic Car Pack v3.5" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('name') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-game" class="block text-xs font-semibold text-[#F2F5F0]">Game</label>
                    <select id="mod-game" wire:model="game_id" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @foreach ($games as $game)
                            <option value="{{ $game->id }}">{{ $game->name }}</option>
                        @endforeach
                    </select>
                    @error('game_id') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-category" class="block text-xs font-semibold text-[#F2F5F0]">Kategori</label>
                    <select id="mod-category" wire:model="category_id" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-pricing" class="block text-xs font-semibold text-[#F2F5F0]">Tipe Mod</label>
                    <select id="mod-pricing" wire:model.live="pricing_type" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        <option value="free">Gratis (Unduh Langsung)</option>
                        <option value="paid">Berbayar (Perlu Transaksi)</option>
                    </select>
                    @error('pricing_type') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                @if ($pricing_type === 'paid')
                    <div>
                        <label for="mod-price" class="block text-xs font-semibold text-[#F2F5F0]">Harga (Rupiah)</label>
                        <input id="mod-price" type="number" min="1000" wire:model="price" placeholder="50000" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('price') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mod-promo" class="block text-xs font-semibold text-[#F2F5F0]">Harga Promo (Opsional)</label>
                        <input id="mod-promo" type="number" min="1000" wire:model="promo_price" placeholder="Kosongkan jika tidak ada promo" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('promo_price') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div>
                    <label for="mod-version" class="block text-xs font-semibold text-[#F2F5F0]">Versi Mod</label>
                    <input id="mod-version" type="text" wire:model="mod_version" placeholder="1.0.0" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('mod_version') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-compat" class="block text-xs font-semibold text-[#F2F5F0]">Versi Game Kompatibel</label>
                    <input id="mod-compat" type="text" wire:model="compatible_game_version" placeholder="Contoh: GTA V Build 3095+ / ETS2 1.53" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('compatible_game_version') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-short" class="block text-xs font-semibold text-[#F2F5F0]">Ringkasan Singkat</label>
                    <input id="mod-short" type="text" wire:model="short_description" placeholder="Ringkasan satu kalimat tentang mod ini" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('short_description') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-desc" class="block text-xs font-semibold text-[#F2F5F0]">Deskripsi Lengkap</label>
                    <textarea id="mod-desc" rows="4" wire:model="description" required class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3.5 text-sm text-[#F2F5F0]"></textarea>
                    @error('description') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-dep" class="block text-xs font-semibold text-[#F2F5F0]">DLC / Dependency (Opsional)</label>
                    <input id="mod-dep" type="text" wire:model="dependencies" placeholder="Contoh: Script Hook V / Iris Shaders" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                </div>

                <div>
                    <label for="mod-thumb" class="block text-xs font-semibold text-[#F2F5F0]">Thumbnail Preview (Opsional)</label>
                    <input id="mod-thumb" type="file" wire:model="thumbnail" accept="image/png,image/jpeg,image/webp" class="mt-1.5 block w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 py-2 text-xs text-[#A0AAA2] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7CFF00] file:px-3 file:py-1.5 file:text-xs file:font-extrabold file:text-[#080B0A]">
                    @error('thumbnail') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="mod-file" class="block text-xs font-semibold text-[#F2F5F0]">
                        File Arsip Mod (.zip, .scs, .rar, .7z, maks 50 MB) {{ $editingId ? '(Kosongkan jika tidak mengganti file)' : '' }}
                    </label>
                    <input id="mod-file" type="file" wire:model="modFile" class="mt-1.5 block w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 py-2 text-xs text-[#A0AAA2] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7CFF00] file:px-3 file:py-1.5 file:text-xs file:font-extrabold file:text-[#080B0A]">
                    @error('modFile') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mod-guide" class="block text-xs font-semibold text-[#F2F5F0]">Panduan Instalasi (Opsional)</label>
                    <textarea id="mod-guide" rows="3" wire:model="install_guide" class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3.5 text-sm text-[#F2F5F0]"></textarea>
                </div>

                <div>
                    <label for="mod-changelog" class="block text-xs font-semibold text-[#F2F5F0]">Changelog (Opsional)</label>
                    <textarea id="mod-changelog" rows="3" wire:model="changelog" class="mt-1.5 w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3.5 text-sm text-[#F2F5F0]"></textarea>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-6 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B] disabled:opacity-60">
                    <span wire:loading.remove wire:target="saveProduct">{{ $editingId ? 'Simpan Perubahan' : 'Unggah Mod' }}</span>
                    <span wire:loading wire:target="saveProduct">Menyimpan mod...</span>
                </button>
                <button type="button" wire:click="cancelForm" class="inline-flex min-h-[42px] items-center rounded-[10px] border border-[#29342D] px-5 text-xs font-semibold text-[#A0AAA2] hover:text-[#F2F5F0]">
                    Batal
                </button>
            </div>
        </form>
    @endif

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Belum ada mod di toko Anda</h2>
            <p class="mt-2 text-sm text-[#A0AAA2]">Tambahkan mod pertama Anda untuk mulai membagikan mod gratis atau menjual mod berbayar.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($products as $product)
                <article class="flex flex-col justify-between gap-4 rounded-2xl border border-[#29342D] bg-[#101614] p-5 sm:flex-row sm:items-center">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-status-badge :status="$product->status" />
                            <x-status-badge :status="$product->pricing_type" />
                            <span class="text-xs text-[#A0AAA2]">{{ $product->game?->short_code }} &middot; v{{ $product->mod_version }}</span>
                        </div>
                        <h3 class="font-display mt-2 text-base font-bold text-[#F2F5F0]">{{ $product->name }}</h3>
                        <p class="mt-1 text-xs text-[#A0AAA2]">
                            {{ $product->isFree() ? 'Gratis' : 'Rp '.number_format($product->effectivePrice(), 0, ',', '.') }}
                            &middot; {{ $product->downloads_count }} unduhan
                        </p>
                        @if ($product->rejection_reason)
                            <p class="mt-2 text-xs text-[#FF6262]">Alasan penolakan: {{ $product->rejection_reason }}</p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-semibold text-[#F2F5F0]">
                            Pratinjau
                        </a>
                        <button type="button" wire:click="startEdit({{ $product->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#7CFF00]/60 bg-[#151D19] px-4 text-xs font-bold text-[#7CFF00]">
                            Edit
                        </button>
                        <button type="button" wire:click="deleteProduct({{ $product->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#FF6262]/40 px-4 text-xs font-semibold text-[#FF6262]">
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
