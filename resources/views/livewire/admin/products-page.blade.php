<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Moderasi Mod</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Tinjau mod yang diunggah modder sebelum tampil ke publik.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status mod" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            <option value="">Semua Status</option>
            <option value="pending_review">Menunggu Review</option>
            <option value="active">Aktif</option>
            <option value="rejected">Ditolak</option>
            <option value="disabled">Dinonaktifkan</option>
        </select>
    </div>

    <x-admin-nav />

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <p class="text-base font-bold text-[#F8FAFC]">Tidak ada mod pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($products as $product)
                <article class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$product->status" />
                                <x-status-badge :status="$product->pricing_type" />
                                <span class="text-xs text-[#AAB2C5]">{{ $product->game?->short_code }} &middot; {{ $product->category?->name }}</span>
                            </div>
                            <h2 class="mt-2 text-base font-bold text-[#F8FAFC]">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs text-[#AAB2C5]">
                                Modder: {{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}
                                &middot; {{ $product->isFree() ? 'Gratis' : 'Rp '.number_format($product->effectivePrice(), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-xs font-semibold text-[#F8FAFC]">
                                Lihat Detail
                            </a>
                            <button type="button" wire:click="approve({{ $product->id }})" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#22C55E] px-3.5 text-xs font-bold text-[#0B0D17]">
                                Setujui
                            </button>
                            <button type="button" wire:click="toggleFeatured({{ $product->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#7C4DFF]/60 bg-[#1A1E30] px-3.5 text-xs font-semibold text-[#A78BFA]">
                                {{ $product->is_featured ? 'Hapus Pilihan' : 'Jadikan Pilihan' }}
                            </button>
                            <button type="button" wire:click="disable({{ $product->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] px-3.5 text-xs font-semibold text-[#AAB2C5]">
                                Nonaktifkan
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2 border-t border-[#30364D] pt-4 sm:flex-row">
                        <input
                            type="text"
                            wire:model="rejectReasons.{{ $product->id }}"
                            placeholder="Alasan penolakan (wajib diisi jika menolak mod)"
                            class="min-h-[44px] flex-1 rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 text-xs text-[#F8FAFC]"
                        >
                        <button type="button" wire:click="reject({{ $product->id }})" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#F87171]/50 px-4 text-xs font-semibold text-[#F87171]">
                            Tolak Mod
                        </button>
                    </div>
                    @error("rejectReasons.{$product->id}")
                        <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p>
                    @enderror
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
