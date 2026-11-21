<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Kelola Game & Kategori Konten</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Tambahkan game baru atau kategori konten digital tanpa perlu mengubah kode frontend.</p>
    </div>

    <x-admin-nav />

    <div class="grid gap-8 lg:grid-cols-12">
        <section class="space-y-5 lg:col-span-6">
            <form wire:submit="addGame" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 space-y-4">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Tambah Game Baru</h2>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="game-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Game</label>
                        <input id="game-name" type="text" wire:model="gameName" placeholder="Contoh: GTA V / Cyberpunk" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('gameName') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="game-code" class="block text-xs font-semibold text-[#F2F5F0]">Kode Singkat Badge</label>
                        <input id="game-code" type="text" wire:model="gameShortCode" placeholder="Contoh: GTA V" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @error('gameShortCode') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="game-cover" class="block text-xs font-semibold text-[#F2F5F0]">Cover Tema Game</label>
                    <select id="game-cover" wire:model="gameCoverPath" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                        @foreach ($availableCovers as $path => $label)
                            <option value="{{ $path }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="game-desc" class="block text-xs font-semibold text-[#F2F5F0]">Deskripsi Singkat (Opsional)</label>
                    <input id="game-desc" type="text" wire:model="gameDescription" placeholder="Koleksi mod, script, dan add-on" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                </div>

                <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                    Simpan Game
                </button>
            </form>

            <div class="space-y-2.5">
                @foreach ($games as $game)
                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $game->coverUrl() }}" alt="{{ $game->name }}" class="h-12 w-16 rounded-lg border border-[#29342D] object-cover">
                            <div>
                                <p class="text-sm font-bold text-[#F2F5F0]">{{ $game->name }} <span class="text-xs font-semibold text-[#7CFF00]">({{ $game->short_code }})</span></p>
                                <p class="text-xs text-[#A0AAA2]">{{ $game->products_count }} produk</p>
                            </div>
                        </div>
                        @if ($game->products_count === 0)
                            <button type="button" wire:click="deleteGame({{ $game->id }})" class="inline-flex min-h-[38px] items-center rounded-[10px] border border-[#FF6262]/40 px-3 text-xs font-semibold text-[#FF6262]">
                                Hapus
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="space-y-5 lg:col-span-6">
            <form wire:submit="addCategory" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6 space-y-4">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Tambah Kategori Konten</h2>

                <div>
                    <label for="cat-name" class="block text-xs font-semibold text-[#F2F5F0]">Nama Kategori</label>
                    <input id="cat-name" type="text" wire:model="categoryName" placeholder="Contoh: Script / Shader / Resource Pack" required class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                    @error('categoryName') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cat-desc" class="block text-xs font-semibold text-[#F2F5F0]">Deskripsi (Opsional)</label>
                    <input id="cat-desc" type="text" wire:model="categoryDescription" placeholder="Penjelasan singkat kategori" class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0]">
                </div>

                <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                    Simpan Kategori
                </button>
            </form>

            <div class="space-y-2.5">
                @foreach ($categories as $category)
                    <div class="flex items-center justify-between gap-3 rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                        <div>
                            <p class="text-sm font-bold text-[#F2F5F0]">{{ $category->name }}</p>
                            <p class="text-xs text-[#A0AAA2]">{{ $category->products_count }} produk {{ $category->description ? '· '.$category->description : '' }}</p>
                        </div>
                        @if ($category->products_count === 0)
                            <button type="button" wire:click="deleteCategory({{ $category->id }})" class="inline-flex min-h-[38px] items-center rounded-[10px] border border-[#FF6262]/40 px-3 text-xs font-semibold text-[#FF6262]">
                                Hapus
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
