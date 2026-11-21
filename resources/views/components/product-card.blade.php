@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-[#29342D] bg-[#151D19] transition-colors duration-200 hover:border-[#7CFF00]/60 hover:bg-[#1C2721]">
    <a href="{{ route('products.show', $product->slug) }}" class="relative block aspect-[16/10] w-full overflow-hidden bg-[#101614]">
        @if ($product->thumbnail_path)
            <img
                src="{{ asset('storage/'.$product->thumbnail_path) }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-[1.03]"
            >
        @else
            <img
                src="{{ $product->game?->coverUrl() ?? asset('assets/game-covers/open-world.svg') }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-[1.03]"
            >
        @endif

        <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
            <span class="rounded-lg border border-[#29342D] bg-[#080B0A]/90 px-2.5 py-1 text-xs font-bold text-[#F2F5F0]">
                {{ $product->game?->short_code ?? 'MOD' }}
            </span>
            @if ($product->isFree())
                <span class="rounded-lg border border-[#35D07F]/40 bg-[#080B0A]/90 px-2.5 py-1 text-xs font-bold text-[#35D07F]">
                    Gratis
                </span>
            @elseif ($product->promo_price && $product->promo_price < $product->price)
                <span class="rounded-lg border border-[#7CFF00]/40 bg-[#080B0A]/90 px-2.5 py-1 text-xs font-bold text-[#7CFF00]">
                    Promo
                </span>
            @endif
        </div>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="font-display line-clamp-1 text-base font-bold text-[#F2F5F0]">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-[#7CFF00]">
                {{ $product->name }}
            </a>
        </h3>

        <p class="mt-1 line-clamp-1 text-xs font-medium text-[#A0AAA2]">
            {{ $product->category?->name }} &middot; {{ $product->compatible_game_version }}
        </p>

        <div class="mt-2.5 flex items-center justify-between text-xs text-[#A0AAA2]">
            <span>v{{ $product->mod_version }}</span>
            <span>{{ number_format($product->downloads_count) }} unduhan</span>
        </div>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-[#29342D] pt-3.5">
            <div>
                @if ($product->isFree())
                    <p class="font-display text-lg font-extrabold text-[#7CFF00]">Gratis</p>
                @else
                    @if ($product->promo_price && $product->promo_price < $product->price)
                        <p class="text-[11px] text-[#A0AAA2] line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="font-display text-lg font-extrabold text-[#F2F5F0]">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                    @else
                        <p class="font-display text-lg font-extrabold text-[#F2F5F0]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    @endif
                @endif
            </div>

            <a
                href="{{ route('products.show', $product->slug) }}"
                aria-label="{{ $product->isFree() ? 'Unduh '.$product->name : 'Lihat detail '.$product->name }}"
                class="inline-flex min-h-[40px] items-center justify-center gap-1.5 rounded-[10px] border border-[#7CFF00]/50 bg-[#101614] px-3.5 py-2 text-xs font-bold text-[#7CFF00] transition-colors duration-200 hover:bg-[#7CFF00] hover:text-[#080B0A]"
            >
                <img src="{{ asset($product->isFree() ? 'assets/icons/download.svg' : 'assets/icons/cart.svg') }}" alt="" class="h-4 w-4">
                <span>{{ $product->isFree() ? 'Unduh' : 'Detail' }}</span>
            </a>
        </div>
    </div>
</article>
