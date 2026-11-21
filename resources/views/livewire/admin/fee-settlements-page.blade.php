<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Riwayat Setoran Biaya Admin Modder</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Daftar pembayaran setoran biaya admin dari modder yang langsung masuk ke akun pembayaran Admin.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status setoran" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
            <option value="">Semua Status</option>
            <option value="verified">Terverifikasi Lunas</option>
            <option value="pending_verification">Menunggu Verifikasi</option>
            <option value="rejected">Ditolak</option>
        </select>
    </div>

    <x-admin-nav />

    @if ($settlements->isEmpty())
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <p class="font-display text-base font-bold text-[#F2F5F0]">Belum ada setoran biaya admin pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($settlements as $settlement)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $settlement->settlement_number }}</p>
                            <x-status-badge :status="$settlement->status" />
                        </div>
                        <p class="mt-1.5 text-sm text-[#F2F5F0]">
                            Modder: <span class="font-bold">{{ $settlement->seller->sellerProfile?->store_name ?? $settlement->seller->name }}</span>
                            ({{ $settlement->seller->email }})
                        </p>
                        <p class="mt-1 text-xs text-[#A0AAA2]">
                            {{ $settlement->orders_count }} transaksi mod &middot; Dibayar pada {{ $settlement->submitted_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="font-display text-lg font-extrabold text-[#7CFF00]">Rp {{ number_format($settlement->amount, 0, ',', '.') }}</p>
                        <p class="text-xs font-semibold text-[#35D07F]">Masuk ke Akun Admin</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $settlements->links() }}
        </div>
    @endif
</div>
