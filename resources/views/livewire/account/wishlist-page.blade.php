<div class="mx-auto max-w-[1200px] px-4 py-8 lg:px-8">
    <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Wishlist Saya</h1>
    <p class="mt-1 text-sm text-[#AAB2C5]">Daftar mod yang Anda simpan untuk diunduh atau dibeli nanti.</p>

    @if ($items->isEmpty())
        <div class="mt-8 rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Wishlist masih kosong</h2>
            <p class="mt-2 text-sm text-[#AAB2C5]">Simpan mod favorit dari halaman detail produk agar mudah ditemukan kembali.</p>
            <a href="{{ route('catalog') }}" class="mt-5 inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-5 text-sm font-semibold text-white">
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
                        class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#30364D] bg-[#121525] px-4 text-xs font-semibold text-[#F87171] hover:border-[#F87171]/60"
                    >
                        Hapus dari Wishlist
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>
