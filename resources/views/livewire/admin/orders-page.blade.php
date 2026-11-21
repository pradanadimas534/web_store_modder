<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Pantau Transaksi</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Seluruh transaksi mod berbayar antara pembeli dan modder.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status transaksi" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
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
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <p class="font-display text-base font-bold text-[#F2F5F0]">Belum ada transaksi pada filter ini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                            <x-status-badge :status="$order->status" />
                        </div>
                        <p class="mt-1 text-xs text-[#A0AAA2]">
                            Pembeli: {{ $order->buyer->name }} &middot; Modder: {{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}
                            &middot; Harga Mod Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                            + Admin Rp {{ number_format($order->admin_fee, 0, ',', '.') }}
                            = Total Rp {{ number_format($order->total, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if ($order->payment_proof_path)
                            <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-bold text-[#7CFF00]">
                                Bukti Bayar
                            </a>
                        @endif
                        @if (! $order->isCompleted())
                            <button type="button" wire:click="markCompleted({{ $order->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] bg-[#7CFF00] px-3.5 text-xs font-extrabold text-[#080B0A]">
                                Tandai Selesai
                            </button>
                            <button type="button" wire:click="markCancelled({{ $order->id }})" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#FF6262]/40 px-3.5 text-xs font-semibold text-[#FF6262]">
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
