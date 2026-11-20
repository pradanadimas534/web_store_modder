@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-[#30364D] bg-[#1A1E30] transition-colors duration-200 hover:border-[#7C4DFF]/60 hover:bg-[#242943]">
    <a href="{{ route('products.show', $product->slug) }}" class="relative block aspect-[16/10] w-full overflow-hidden bg-[#121525]">
        @if ($product->thumbnail_path)
            <img
                src="{{ asset('storage/'.$product->thumbnail_path) }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="h-full w-full object-cover transition-transform duration-200 group-hover:scale-[1.03]"
            >
        @else
            <div class="flex h-full w-full flex-col items-center justify-center px-4 text-center">
                <span class="text-xs font-semibold tracking-wider text-[#A78BFA]">{{ $product->game?->short_code ?? 'MOD' }}</span>
                <span class="mt-1 line-clamp-2 text-sm font-bold text-[#F8FAFC]">{{ $product->name }}</span>
            </div>
        @endif

        <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
            <span class="rounded-lg border border-[#30364D] bg-[#0B0D17]/90 px-2.5 py-1 text-xs font-semibold text-[#F8FAFC]">
                {{ $product->game?->short_code ?? 'MOD' }}
            </span>
            @if ($product->isFree())
                <span class="rounded-lg border border-[#22C55E]/40 bg-[#0B0D17]/90 px-2.5 py-1 text-xs font-semibold text-[#22C55E]">
                    Gratis
                </span>
            @else
                <span class="rounded-lg border border-[#FBBF24]/40 bg-[#0B0D17]/90 px-2.5 py-1 text-xs font-semibold text-[#FBBF24]">
                    Berbayar
                </span>
            @endif
        </div>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <div class="flex items-center justify-between gap-2 text-xs text-[#AAB2C5]">
            <span>{{ $product->category?->name }}</span>
            <span>v{{ $product->mod_version }}</span>
        </div>

        <h3 class="mt-1.5 line-clamp-1 text-base font-bold text-[#F8FAFC]">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-[#A78BFA]">
                {{ $product->name }}
            </a>
        </h3>

        <p class="mt-1 line-clamp-2 text-sm text-[#AAB2C5]">
            {{ $product->short_description }}
        </p>

        <div class="mt-3 text-xs text-[#AAB2C5]">
            Kompatibel: <span class="font-medium text-[#F8FAFC]">{{ $product->compatible_game_version }}</span>
        </div>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-[#30364D] pt-3.5">
            <div>
                @if ($product->isFree())
                    <p class="text-base font-extrabold text-[#22C55E]">Gratis</p>
                @else
                    @if ($product->promo_price && $product->promo_price < $product->price)
                        <p class="text-xs text-[#AAB2C5] line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-base font-extrabold text-[#F8FAFC]">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                    @else
                        <p class="text-base font-extrabold text-[#F8FAFC]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    @endif
                @endif
            </div>

            <a
                href="{{ route('products.show', $product->slug) }}"
                class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-[#7C4DFF] px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-[#6D3DF5]"
            >
                {{ $product->isFree() ? 'Unduh' : 'Detail' }}
            </a>
        </div>
    </div>
</article>
