<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Kelola Iklan (AdSense)</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Pantau pembayaran iklan yang masuk ke akun Admin serta kelola penayangan slot banner.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <select wire:model.live="paymentStatusFilter" aria-label="Filter status pembayaran iklan" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                <option value="">Semua Status Bayar</option>
                <option value="paid">Lunas</option>
                <option value="unpaid">Belum Dibayar</option>
                <option value="rejected">Ditolak</option>
            </select>

            <select wire:model.live="statusFilter" aria-label="Filter status tayang iklan" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
                <option value="">Semua Status Tayang</option>
                <option value="active">Aktif</option>
                <option value="paused">Dijeda</option>
                <option value="pending">Menunggu Review</option>
                <option value="rejected">Ditolak</option>
            </select>
        </div>
    </div>

    <x-admin-nav />

    @if ($ads->isEmpty())
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <p class="font-display text-base font-bold text-[#F2F5F0]">Belum ada iklan pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($ads as $ad)
                <article class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="min-w-0 max-w-2xl">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :status="$ad->status" />
                                @if ($ad->payment_status)
                                    <x-status-badge :status="$ad->payment_status" />
                                @endif
                                <span class="rounded-md border border-[#29342D] bg-[#151D19] px-2 py-0.5 text-xs font-bold text-[#7CFF00]">
                                    {{ $ad->placement->label() }}
                                </span>
                            </div>

                            <h2 class="font-display mt-2.5 text-base font-bold text-[#F2F5F0]">{{ $ad->title }}</h2>
                            <p class="mt-1 text-xs leading-relaxed text-[#A0AAA2]">{{ $ad->description }}</p>

                            <p class="mt-2.5 text-xs text-[#F2F5F0]">
                                Tarif Slot: Rp {{ number_format($ad->slot_price, 0, ',', '.') }}
                                + Biaya Admin: Rp {{ number_format($ad->admin_fee, 0, ',', '.') }}
                                = <span class="font-extrabold text-[#7CFF00]">Total Terbayar Rp {{ number_format($ad->total_price, 0, ',', '.') }}</span>
                            </p>
                            <p class="mt-1 text-xs text-[#A0AAA2]">
                                Pengiklan: <span class="font-semibold text-[#F2F5F0]">{{ $ad->advertiser_name }}</span> ({{ $ad->advertiser_email }})
                                &middot; Impresi: {{ number_format($ad->impressions_count) }}
                                &middot; Klik: {{ number_format($ad->clicks_count) }}
                            </p>
                            <p class="mt-1 truncate text-xs text-[#B6E879]">URL Tujuan: {{ $ad->target_url }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($ad->status->value !== 'active')
                                <button type="button" wire:click="verifyPaymentAndActivate({{ $ad->id }})" class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
                                    Aktifkan
                                </button>
                            @else
                                <button type="button" wire:click="setStatus({{ $ad->id }}, 'paused')" class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#F5C451]/50 px-4 text-xs font-semibold text-[#F5C451]">
                                    Jeda Tayang
                                </button>
                            @endif

                            <button
                                type="button"
                                wire:click="deleteAd({{ $ad->id }})"
                                class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#29342D] px-4 text-xs font-semibold text-[#A0AAA2] hover:border-[#FF6262]/50 hover:text-[#FF6262]"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $ads->links() }}
        </div>
    @endif
</div>
