<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Pesanan Masuk</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Periksa bukti transfer pembeli lalu verifikasi agar tautan unduhan mod terbuka.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status pesanan" class="min-h-[44px] rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]">
            <option value="">Semua Status</option>
            <option value="pending_payment">Menunggu Pembayaran</option>
            <option value="pending_verification">Menunggu Verifikasi</option>
            <option value="completed">Berhasil</option>
            <option value="rejected">Ditolak</option>
            <option value="cancelled">Dibatalkan</option>
        </select>
    </div>

    <x-seller-nav />

    @if ($orders->isEmpty())
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Belum ada pesanan masuk</h2>
            <p class="mt-2 text-sm text-[#AAB2C5]">Pesanan mod berbayar dari pembeli akan tampil di sini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <article class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#30364D] pb-4">
                        <div>
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#AAB2C5]">Pembeli: {{ $order->buyer->name }} ({{ $order->buyer->email }}) &middot; {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-base font-extrabold text-[#22C55E]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                    </div>

                    <div class="mt-4 grid items-center gap-4 lg:grid-cols-12">
                        <div class="lg:col-span-6">
                            <p class="text-xs font-semibold text-[#AAB2C5]">Produk:</p>
                            @foreach ($order->items as $item)
                                <p class="mt-1 text-sm font-semibold text-[#F8FAFC]">{{ $item->product_name_snapshot }}</p>
                            @endforeach
                            <p class="mt-2 text-xs text-[#AAB2C5]">
                                Tujuan: {{ $order->payment_method_snapshot['provider_name'] ?? '-' }} ({{ $order->payment_method_snapshot['account_number'] ?? '-' }})
                            </p>
                            @if ($order->payment_note)
                                <p class="mt-1 text-xs text-[#F8FAFC]">Catatan pembeli: {{ $order->payment_note }}</p>
                            @endif
                            @if ($order->payment_proof_path)
                                <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-xs font-semibold text-[#A78BFA] hover:border-[#7C4DFF]">
                                    Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 lg:col-span-6 lg:justify-end">
                            @if ($order->status->value === 'pending_verification')
                                <button
                                    type="button"
                                    wire:click="approveOrder({{ $order->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menyetujui pesanan {{ $order->order_number }}? Akses unduhan mod akan langsung terbuka untuk pembeli."
                                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-[#22C55E] px-4 text-xs font-bold text-[#0B0D17] hover:bg-[#16A34A]"
                                >
                                    Verifikasi
                                </button>
                                <button
                                    type="button"
                                    wire:click="openRejectModal({{ $order->id }})"
                                    class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#F87171]/50 px-4 text-xs font-semibold text-[#F87171] hover:bg-[#F87171]/15"
                                >
                                    Tolak
                                </button>
                            @endif
                            <a href="{{ route('orders.payment', $order->order_number) }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-xs font-semibold text-[#F8FAFC] hover:border-[#7C4DFF]">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

    @if ($rejectingOrderId)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-[#0B0D17]/80 px-4"
            x-data
            x-on:keydown.escape.window="$wire.closeRejectModal()"
            wire:click.self="closeRejectModal"
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="reject-modal-title"
                class="w-full max-w-md rounded-2xl border border-[#30364D] bg-[#121525] p-6"
            >
                <h2 id="reject-modal-title" class="text-base font-bold text-[#F8FAFC]">Tolak Bukti Pembayaran</h2>
                <p class="mt-1 text-xs text-[#AAB2C5]">Alasan ini akan ditampilkan ke pembeli agar mereka bisa mengirim ulang bukti transfer yang benar.</p>

                <form wire:submit="rejectOrder" class="mt-4 space-y-3">
                    <label for="reject-reason" class="sr-only">Alasan penolakan</label>
                    <textarea
                        id="reject-reason"
                        rows="3"
                        wire:model="rejectReason"
                        autofocus
                        placeholder="Contoh: nominal transfer tidak sesuai tagihan"
                        class="w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                    ></textarea>
                    @error('rejectReason')
                        <p class="text-xs text-[#F87171]">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="closeRejectModal"
                            class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] px-4 text-xs font-semibold text-[#AAB2C5] hover:text-[#F8FAFC]"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex min-h-[44px] items-center rounded-xl bg-[#F87171] px-4 text-xs font-bold text-[#0B0D17] hover:bg-[#EF4444] disabled:opacity-60"
                        >
                            Tolak Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
