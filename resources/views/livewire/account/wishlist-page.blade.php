<div class="mx-auto max-w-[1200px] px-4 py-8 lg:px-8">
    <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Wishlist Saya</h1>
    <p class="mt-1 text-sm text-[#A0AAA2]">Daftar mod yang Anda simpan untuk diunduh atau dibeli nanti.</p>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Wishlist masih kosong</h2>
            <p class="mt-2 text-sm text-[#A0AAA2]">Simpan mod favorit dari halaman detail produk agar mudah ditemukan kembali.</p>
            <a href="{{ route('catalog') }}" class="mt-5 inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A]">
                Jelajahi Katalog
            </a>
        </div>
    @else
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($items as $item)
                <div class="flex flex-col gap-2">
                    <x-product-card :product="$item->product" />
                    <button
                        type="button"
                        wire:click="remove({{ $item->id }})"
                        class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#29342D] bg-[#101614] px-4 text-xs font-semibold text-[#FF6262] hover:border-[#FF6262]/60"
                    >
                        Hapus dari Wishlist
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
