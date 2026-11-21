<div class="mx-auto max-w-[1040px] px-4 py-8 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#29342D] pb-5">
        <div>
            <p class="text-xs font-semibold text-[#A0AAA2]">Nomor Pesanan</p>
            <h1 class="font-display mt-1 text-2xl font-extrabold text-[#F2F5F0]">{{ $order->order_number }}</h1>
        </div>
        <x-status-badge :status="$order->status" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-7">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Bukti Transfer Pembayaran</h2>

                @if ($order->payment_proof_path)
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden rounded-xl border border-[#29342D] bg-[#151D19]">
                            <img
                                src="{{ route('orders.proof', $order) }}"
                                alt="Bukti transfer pesanan {{ $order->order_number }}"
                                class="max-h-80 w-full object-contain"
                            >
                        </a>
                        <a
                            href="{{ route('orders.proof', $order) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-[42px] items-center rounded-[10px] border border-[#7CFF00]/60 bg-[#151D19] px-4 text-xs font-bold text-[#7CFF00] hover:bg-[#1C2721]"
                        >
                            Buka Gambar Ukuran Penuh
                        </a>
                    </div>
                @else
                    <p class="mt-3 rounded-xl border border-[#F5C451]/40 bg-[#F5C451]/15 px-4 py-3 text-xs text-[#F5C451]">
                        Pembeli belum mengunggah bukti transfer.
                    </p>
                @endif

                @if ($canVerify && $order->status->value === 'pending_verification')
                    <div class="mt-5 flex flex-wrap gap-2 border-t border-[#29342D] pt-4">
                        <button
                            type="button"
                            wire:click="openApproveModal"
                            class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]"
                        >
                            Verifikasi
                        </button>
                        <button
                            type="button"
                            wire:click="openRejectModal"
                            class="inline-flex min-h-[40px] items-center justify-center rounded-[10px] border border-[#FF6262]/50 bg-[#151D19] px-4 text-xs font-semibold text-[#FF6262] hover:bg-[#FF6262]/15"
                        >
                            Tolak
                        </button>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Informasi Pembeli & Pesanan</h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Nama Pembeli</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->buyer->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Email Pembeli</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->buyer->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Tanggal Pesanan</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Waktu Unggah Bukti</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->paid_at ? $order->paid_at->format('d M Y H:i') : 'Belum diunggah' }}</dd>
                    </div>
                    @if ($order->payment_note)
                        <div class="flex justify-between gap-4">
                            <dt class="text-[#A0AAA2]">Catatan Pembeli</dt>
                            <dd class="text-right font-semibold text-[#F2F5F0]">{{ $order->payment_note }}</dd>
                        </div>
                    @endif
                    @if ($order->verified_at)
                        <div class="flex justify-between gap-4 border-t border-[#29342D] pt-2.5">
                            <dt class="text-[#A0AAA2]">Diverifikasi</dt>
                            <dd class="text-right font-semibold text-[#35D07F]">
                                {{ $order->verified_at->format('d M Y H:i') }}{{ $order->verifier ? ' oleh '.$order->verifier->name : '' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Tujuan Pembayaran Modder</h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Toko Modder</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Metode</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->payment_method_snapshot['provider_name'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Nomor Rekening / Tujuan</dt>
                        <dd class="font-extrabold text-[#7CFF00]">{{ $order->payment_method_snapshot['account_number'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $order->payment_method_snapshot['account_holder'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-[#29342D] pt-3">
                        <dt class="text-[#A0AAA2]">Harga Mod</dt>
                        <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#A0AAA2]">Biaya Admin Platform</dt>
                        <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-[#29342D] pt-3 text-base font-extrabold">
                        <dt class="text-[#F2F5F0]">Total Transfer</dt>
                        <dd class="font-display text-[#7CFF00]">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
                @if (! empty($order->payment_method_snapshot['instructions']))
                    <p class="mt-4 rounded-xl border border-[#29342D] bg-[#151D19] p-3 text-xs text-[#A0AAA2]">
                        {{ $order->payment_method_snapshot['instructions'] }}
                    </p>
                @endif
            </div>

            @if ($canVerify)
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                    <label for="seller-note" class="sr-only">Catatan Modder</label>
                    <textarea
                        id="seller-note"
                        rows="3"
                        wire:model.live.debounce.600ms="sellerNote"
                        placeholder="Tulis catatan untuk pembeli atau pesanan ini..."
                        class="w-full rounded-[10px] border border-[#29342D] bg-[#151D19] p-3 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60 focus:border-[#7CFF00]"
                    ></textarea>
                    @error('sellerNote') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                </div>
            @elseif ($order->seller_note)
                <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-4">
                    <p class="whitespace-pre-line rounded-[10px] border border-[#29342D] bg-[#151D19] p-3 text-sm leading-relaxed text-[#F2F5F0]">{{ $order->seller_note }}</p>
                </div>
            @endif

            @if ($order->status->value === 'rejected' && $order->rejection_reason)
                <div role="alert" class="rounded-2xl border border-[#FF6262]/40 bg-[#FF6262]/15 p-5 text-sm text-[#FF6262]">
                    <p class="font-bold">Bukti pembayaran sebelumnya ditolak:</p>
                    <p class="mt-1">{{ $order->rejection_reason }}</p>
                </div>
            @endif

            @if ($order->buyer_id === auth()->id() && $order->canUploadProof())
                <form wire:submit="submitProof" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                    <h2 class="font-display text-base font-bold text-[#F2F5F0]">Unggah Bukti Pembayaran</h2>
                    <p class="mt-1 text-xs text-[#A0AAA2]">Format JPG, PNG, atau WEBP maksimal 4 MB.</p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="proof-file" class="block text-xs font-semibold text-[#F2F5F0]">File Bukti Transfer</label>
                            <input
                                id="proof-file"
                                type="file"
                                wire:model="paymentProof"
                                accept="image/png,image/jpeg,image/webp"
                                required
                                class="mt-1.5 block w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 py-2.5 text-xs text-[#A0AAA2] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7CFF00] file:px-3 file:py-1.5 file:text-xs file:font-extrabold file:text-[#080B0A]"
                            >
                            @error('paymentProof') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="proof-note" class="block text-xs font-semibold text-[#F2F5F0]">Catatan (Opsional)</label>
                            <input
                                id="proof-note"
                                type="text"
                                wire:model="paymentNote"
                                placeholder="Nama pengirim atau waktu transfer"
                                class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]/60"
                            >
                            @error('paymentNote') <p class="mt-1 text-xs text-[#FF6262]">{{ $message }}</p> @enderror
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] hover:bg-[#9BFF3B] disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="submitProof">Kirim Bukti Pembayaran</span>
                            <span wire:loading wire:target="submitProof">Mengunggah bukti...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="space-y-5 lg:col-span-5">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Item Mod dalam Pesanan</h2>

                <div class="mt-4 space-y-3">
                    @foreach ($order->items as $item)
                        <div class="rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $item->product_name_snapshot }}</p>
                            <p class="mt-1 text-xs text-[#A0AAA2]">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</p>

                            @if ($order->isCompleted() && $order->buyer_id === auth()->id() && isset($downloadLinks[$item->id]))
                                <a
                                    href="{{ $downloadLinks[$item->id] }}"
                                    class="mt-3 inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]"
                                >
                                    Unduh File Mod (Tautan Aman)
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <a href="{{ $backRoute }}" class="mt-5 inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-bold text-[#F2F5F0] hover:border-[#7CFF00]">
                    Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>

    @if ($showApproveModal)
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
                aria-labelledby="detail-approve-modal-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="detail-approve-modal-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Konfirmasi Persetujuan Pesanan</h2>
                    <span class="rounded-lg border border-[#35D07F]/40 bg-[#35D07F]/15 px-2.5 py-0.5 text-xs font-bold text-[#35D07F]">Verifikasi</span>
                </div>

                <p class="mt-4 text-sm leading-relaxed text-[#A0AAA2]">
                    Apakah Anda yakin ingin menyetujui pesanan <span class="font-bold text-[#F2F5F0]">{{ $order->order_number }}</span>? Akses unduhan mod akan langsung terbuka untuk pembeli.
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

    @if ($showRejectModal)
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
                aria-labelledby="detail-reject-modal-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="detail-reject-modal-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Tolak Bukti Pembayaran</h2>
                    <span class="rounded-lg border border-[#FF6262]/40 bg-[#FF6262]/15 px-2.5 py-0.5 text-xs font-bold text-[#FF6262]">Penolakan</span>
                </div>

                <p class="mt-3 text-xs leading-relaxed text-[#A0AAA2]">
                    Masukkan alasan penolakan untuk pesanan <span class="font-bold text-[#F2F5F0]">{{ $order->order_number }}</span> agar pembeli dapat mengirim ulang bukti transfer yang sesuai.
                </p>

                <form wire:submit="rejectOrder" class="mt-4 space-y-4">
                    <div>
                        <label for="detail-reject-reason" class="mb-1.5 block text-xs font-semibold text-[#F2F5F0]">Alasan Penolakan</label>
                        <textarea
                            id="detail-reject-reason"
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
