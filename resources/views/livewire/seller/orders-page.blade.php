<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Pesanan Masuk</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Periksa bukti transfer pembeli lalu verifikasi agar tautan unduhan mod terbuka.</p>
        </div>

        <select wire:model.live="statusFilter" aria-label="Filter status pesanan" class="min-h-[42px] rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-semibold text-[#F2F5F0]">
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
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Belum ada pesanan masuk</h2>
            <p class="mt-2 text-sm text-[#A0AAA2]">Pesanan mod berbayar dari pembeli akan tampil di sini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($orders as $order)
                <article class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#29342D] pb-4">
                        <div>
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#A0AAA2]">Pembeli: {{ $order->buyer->name }} ({{ $order->buyer->email }}) &middot; {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-display text-base font-extrabold text-[#7CFF00]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                    </div>

                    <div class="mt-4 grid items-center gap-4 lg:grid-cols-12">
                        <div class="lg:col-span-6">
                            <p class="text-xs font-semibold text-[#A0AAA2]">Produk:</p>
                            @foreach ($order->items as $item)
                                <p class="mt-1 text-sm font-semibold text-[#F2F5F0]">{{ $item->product_name_snapshot }}</p>
                            @endforeach
                            <p class="mt-2 text-xs text-[#A0AAA2]">
                                Tujuan: {{ $order->payment_method_snapshot['provider_name'] ?? '-' }} ({{ $order->payment_method_snapshot['account_number'] ?? '-' }})
                            </p>
                            @if ($order->payment_note)
                                <p class="mt-1 text-xs text-[#F2F5F0]">Catatan pembeli: {{ $order->payment_note }}</p>
                            @endif
                            @if ($order->payment_proof_path)
                                <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-bold text-[#7CFF00] hover:border-[#7CFF00]">
                                    Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 lg:col-span-6 lg:justify-end">
                            @if ($order->status->value === 'pending_verification')
                                <button
                                    type="button"
                                    wire:click="openApproveModal({{ $order->id }})"
                                    class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]"
                                >
                                    Verifikasi
                                </button>
                                <button
                                    type="button"
                                    wire:click="openRejectModal({{ $order->id }})"
                                    class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#FF6262]/50 bg-[#151D19] px-4 text-xs font-semibold text-[#FF6262] hover:bg-[#FF6262]/15"
                                >
                                    Tolak
                                </button>
                            @endif
                            <a href="{{ route('orders.payment', $order->order_number) }}" class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-semibold text-[#F2F5F0] hover:border-[#7CFF00]">
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

    @if ($approvingOrderId)
        <div
            style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: rgba(8, 11, 10, 0.85); backdrop-filter: blur(4px); padding: 1rem;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-data
            x-on:keydown.escape.window="$wire.closeApproveModal()"
            wire:click.self="closeApproveModal"
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="approve-modal-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="approve-modal-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Konfirmasi Persetujuan Pesanan</h2>
                    <span class="rounded-lg border border-[#35D07F]/40 bg-[#35D07F]/15 px-2.5 py-0.5 text-xs font-bold text-[#35D07F]">Verifikasi</span>
                </div>

                <p class="mt-4 text-sm leading-relaxed text-[#A0AAA2]">
                    Apakah Anda yakin ingin menyetujui pesanan <span class="font-bold text-[#F2F5F0]">{{ $approvingOrderNumber }}</span>? Akses unduhan mod akan langsung terbuka untuk pembeli.
                </p>

                <div class="mt-6 grid grid-cols-2 gap-3 border-t border-[#29342D] pt-4">
                    <button
                        type="button"
                        wire:click="closeApproveModal"
                        style="background-color: #1C2721; color: #F2F5F0; border: 1px solid #29342D;"
                        class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#29342D] bg-[#1C2721] px-4 text-xs font-bold text-[#F2F5F0]"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        wire:click="approveOrder"
                        wire:loading.attr="disabled"
                        style="background-color: #7CFF00; color: #080B0A;"
                        class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B] disabled:opacity-60"
                    >
                        Ya, Setujui Pesanan
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($rejectingOrderId)
        <div
            style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: rgba(8, 11, 10, 0.85); backdrop-filter: blur(4px); padding: 1rem;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-data
            x-on:keydown.escape.window="$wire.closeRejectModal()"
            wire:click.self="closeRejectModal"
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="reject-modal-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="reject-modal-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Tolak Bukti Pembayaran</h2>
                    <span class="rounded-lg border border-[#FF6262]/40 bg-[#FF6262]/15 px-2.5 py-0.5 text-xs font-bold text-[#FF6262]">Penolakan</span>
                </div>

                <p class="mt-3 text-xs leading-relaxed text-[#A0AAA2]">
                    Masukkan alasan penolakan untuk pesanan <span class="font-bold text-[#F2F5F0]">{{ $rejectingOrderNumber }}</span> agar pembeli dapat mengirim ulang bukti transfer yang sesuai.
                </p>

                <form wire:submit="rejectOrder" class="mt-4 space-y-4">
                    <div>
                        <label for="reject-reason" class="mb-1.5 block text-xs font-semibold text-[#F2F5F0]">Alasan Penolakan</label>
                        <textarea
                            id="reject-reason"
                            rows="3"
                            wire:model="rejectReason"
                            autofocus
                            placeholder="Contoh: nominal transfer tidak sesuai tagihan"
                            class="w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                        ></textarea>
                        @error('rejectReason')
                            <p class="mt-1 text-xs font-semibold text-[#FF6262]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 border-t border-[#29342D] pt-4">
                        <button
                            type="button"
                            wire:click="closeRejectModal"
                            style="background-color: #1C2721; color: #F2F5F0; border: 1px solid #29342D;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#29342D] bg-[#1C2721] px-4 text-xs font-bold text-[#F2F5F0]"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            style="background-color: #EF4444; color: #FFFFFF;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#EF4444] px-4 text-xs font-extrabold text-white hover:bg-[#DC2626] disabled:opacity-60"
                        >
                            Tolak Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
