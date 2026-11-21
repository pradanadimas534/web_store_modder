@if ($ad)
    @if ($placementEnum->value === 'sidebar_promo')
        <aside aria-label="Promosi Sponsor" class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <div class="mb-2 flex items-center justify-between gap-2">
                <span class="rounded-md border border-[#7CFF00]/40 bg-[#151D19] px-2 py-0.5 text-xs font-bold text-[#7CFF00]">Sponsor</span>
                <span class="truncate text-xs text-[#A0AAA2]">{{ $ad->advertiser_name }}</span>
            </div>
            @if ($ad->banner_path)
                <img
                    src="{{ asset('storage/'.$ad->banner_path) }}"
                    alt="{{ $ad->title }}"
                    loading="lazy"
                    class="mb-3 aspect-video w-full rounded-xl object-cover"
                >
            @endif
            <h3 class="font-display text-sm font-bold text-[#F2F5F0]">{{ $ad->title }}</h3>
            <p class="mt-1 text-xs leading-relaxed text-[#A0AAA2]">{{ $ad->description }}</p>
            <a
                href="{{ route('ads.redirect', $ad) }}"
                target="_blank"
                rel="noopener noreferrer sponsored"
                class="mt-3 inline-flex min-h-[42px] w-full items-center justify-center rounded-[10px] border border-[#7CFF00]/60 bg-[#151D19] px-4 py-2 text-xs font-bold text-[#7CFF00] transition-colors duration-200 hover:bg-[#7CFF00] hover:text-[#080B0A]"
            >
                {{ $ad->cta_text }}
            </a>
        </aside>
    @elseif ($placementEnum->value === 'footer_strip')
        <aside aria-label="Promosi Sponsor" class="border-t border-[#29342D] bg-[#101614]">
            <div class="mx-auto flex max-w-[1360px] flex-col items-start justify-between gap-3 px-4 py-4 sm:flex-row sm:items-center lg:px-8">
                <div class="flex items-start gap-3 sm:items-center">
                    <span class="shrink-0 rounded-md border border-[#7CFF00]/40 bg-[#151D19] px-2 py-0.5 text-xs font-bold text-[#7CFF00]">Sponsor</span>
                    <div class="min-w-0">
                        <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $ad->title }}</p>
                        <p class="text-xs text-[#A0AAA2]">{{ $ad->description }}</p>
                    </div>
                </div>
                <a
                    href="{{ route('ads.redirect', $ad) }}"
                    target="_blank"
                    rel="noopener noreferrer sponsored"
                    class="inline-flex min-h-[42px] shrink-0 items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 py-2 text-xs font-bold text-[#F2F5F0] transition-colors duration-200 hover:border-[#7CFF00] hover:text-[#7CFF00]"
                >
                    {{ $ad->cta_text }}
                </a>
            </div>
        </aside>
    @else
        <aside aria-label="Banner Promosi" class="rounded-2xl border border-[#29342D] bg-[#101614] p-4 sm:p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @if ($ad->banner_path)
                        <img
                            src="{{ asset('storage/'.$ad->banner_path) }}"
                            alt="{{ $ad->title }}"
                            loading="lazy"
                            class="h-20 w-full shrink-0 rounded-xl object-cover sm:w-36"
                        >
                    @endif
                    <div class="min-w-0">
                        <div class="mb-1 flex items-center gap-2">
                            <span class="rounded-md border border-[#7CFF00]/40 bg-[#151D19] px-2 py-0.5 text-xs font-bold text-[#7CFF00]">Sponsor</span>
                            <span class="text-xs text-[#A0AAA2]">{{ $ad->advertiser_name }}</span>
                        </div>
                        <h3 class="font-display text-base font-bold text-[#F2F5F0]">{{ $ad->title }}</h3>
                        <p class="mt-0.5 text-sm text-[#A0AAA2]">{{ $ad->description }}</p>
                    </div>
                </div>
                <a
                    href="{{ route('ads.redirect', $ad) }}"
                    target="_blank"
                    rel="noopener noreferrer sponsored"
                    class="inline-flex min-h-[42px] shrink-0 items-center justify-center rounded-[10px] bg-[#7CFF00] px-5 py-2.5 text-xs font-extrabold text-[#080B0A] transition-colors duration-200 hover:bg-[#9BFF3B]"
                >
                    {{ $ad->cta_text }}
                </a>
            </div>
        </aside>
    @endif
@endif
