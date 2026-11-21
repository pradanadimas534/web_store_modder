<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Moderasi Mod</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Tinjau mod yang diunggah modder sebelum tampil ke publik.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status mod" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
            <option value="">Semua Status</option>
            <option value="pending_review">Menunggu Review</option>
            <option value="active">Aktif</option>
            <option value="rejected">Ditolak</option>
            <option value="disabled">Dinonaktifkan</option>
        </select>
    </div>

    <x-admin-nav />

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <p class="font-display text-base font-bold text-[#F2F5F0]">Tidak ada mod pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($products as $product)
                <article class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$product->status" />
                                <x-status-badge :status="$product->pricing_type" />
                                <span class="text-xs text-[#A0AAA2]">{{ $product->game?->short_code }} &middot; {{ $product->category?->name }}</span>
                            </div>
                            <h2 class="font-display mt-2 text-base font-bold text-[#F2F5F0]">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs text-[#A0AAA2]">
                                Modder: {{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}
                                &middot; {{ $product->isFree() ? 'Gratis' : 'Rp '.number_format($product->effectivePrice(), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                                Lihat Detail
                            </a>
                            <button type="button" wire:click="approve({{ $product->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] bg-[#7CFF00] px-3.5 text-xs font-extrabold text-[#080B0A]">
                                Setujui
                            </button>
                            <button type="button" wire:click="toggleFeatured({{ $product->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#7CFF00]/60 bg-[#151D19] px-3.5 text-xs font-bold text-[#7CFF00]">
                                {{ $product->is_featured ? 'Hapus Pilihan' : 'Jadikan Pilihan' }}
                            </button>
                            <button type="button" wire:click="disable({{ $product->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] px-3.5 text-xs font-semibold text-[#A0AAA2]">
                                Nonaktifkan
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2 border-t border-[#29342D] pt-4 sm:flex-row">
                        <input
                            type="text"
                            wire:model="rejectReasons.{{ $product->id }}"
                            placeholder="Alasan penolakan (wajib diisi jika menolak mod)"
                            class="min-h-[42px] flex-1 rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 text-xs text-[#F2F5F0]"
                        >
                        <button type="button" wire:click="reject({{ $product->id }})" class="inline-flex min-h-[42px] items-center justify-center rounded-[10px] border border-[#FF6262]/50 px-4 text-xs font-semibold text-[#FF6262]">
                            Tolak Mod
                        </button>
                    </div>
                    @error("rejectReasons.{$product->id}")
                        <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p>
                    @enderror
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
