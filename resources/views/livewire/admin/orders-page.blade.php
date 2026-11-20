<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Pantau Transaksi</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Seluruh transaksi mod berbayar antara pembeli dan modder.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status transaksi" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            <option value="">Semua Status</option>
            <option value="pending_payment">Menunggu Pembayaran</option>
            <option value="pending_verification">Menunggu Verifikasi</option>
            <option value="completed">Berhasil</option>
            <option value="rejected">Ditolak</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    <x-admin-nav />

    @if ($orders->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <p class="text-base font-bold text-[#F8FAFC]">Belum ada transaksi pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                            <x-status-badge :status="$order->status" />
                        </div>
                        <p class="mt-1 text-xs text-[#AAB2C5]">
                            Pembeli: {{ $order->buyer->name }} &middot; Modder: {{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}
                            &middot; Harga Mod Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                            + Admin Rp {{ number_format($order->admin_fee, 0, ',', '.') }}
                            = Total Rp {{ number_format($order->total, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($order->payment_proof_path)
                            <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-xs font-semibold text-[#A78BFA]">
                                Bukti Bayar
                            </a>
                        @endif
                        @if (! $order->isCompleted())
                            <button type="button" wire:click="markCompleted({{ $order->id }})" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#22C55E] px-3.5 text-xs font-bold text-[#0B0D17]">
                                Tandai Selesai
                            </button>
                            <button type="button" wire:click="markCancelled({{ $order->id }})" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#F87171]/40 px-3.5 text-xs font-semibold text-[#F87171]">
                                Batalkan
                            </button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
