<div class="mx-auto max-w-[1040px] px-4 py-8 lg:px-8">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[#30364D] pb-5">
        <div>
            <p class="text-xs font-semibold text-[#AAB2C5]">Nomor Pesanan</p>
            <h1 class="mt-1 text-2xl font-extrabold text-[#F8FAFC]">{{ $order->order_number }}</h1>
        </div>
        <x-status-badge :status="$order->status" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-7">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Bukti Transfer Pembayaran</h2>

                @if ($order->payment_proof_path)
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('orders.proof', $order) }}" target="_blank" rel="noopener noreferrer" class="block overflow-hidden rounded-xl border border-[#30364D] bg-[#1A1E30]">
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
                            class="inline-flex min-h-[44px] items-center rounded-xl border border-[#7C4DFF]/60 bg-[#1A1E30] px-4 text-xs font-semibold text-[#A78BFA] hover:bg-[#242943]"
                        >
                            Buka Gambar Ukuran Penuh
                        </a>
                    </div>
                @else
                    <p class="mt-3 rounded-xl border border-[#FBBF24]/40 bg-[#FBBF24]/15 px-4 py-3 text-xs text-[#FBBF24]">
                        Pembeli belum mengunggah bukti transfer.
                    </p>
                @endif

                @if ($canVerify && $order->status->value === 'pending_verification')
                    <div class="mt-5 space-y-3 border-t border-[#30364D] pt-4">
                        <button
                            type="button"
                            wire:click="approveOrder"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-sm font-bold text-[#0B0D17] hover:bg-[#16A34A]"
                        >
                            Verifikasi Pembayaran & Buka Akses Unduh
                        </button>

                        <div class="flex flex-col gap-2 sm:flex-row">
                            <input
                                type="text"
                                wire:model="rejectReason"
                                placeholder="Alasan penolakan jika bukti tidak sesuai"
                                class="min-h-[44px] flex-1 rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 text-xs text-[#F8FAFC]"
                            >
                            <button
                                type="button"
                                wire:click="rejectOrder"
                                class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#F87171]/50 px-4 text-xs font-semibold text-[#F87171] hover:bg-[#F87171]/15"
                            >
                                Tolak Bukti
                            </button>
                        </div>
                        @error('rejectReason')
                            <p class="text-xs text-[#F87171]">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Informasi Pembeli & Pesanan</h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Nama Pembeli</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->buyer->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Email Pembeli</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->buyer->email }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Tanggal Pesanan</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Waktu Unggah Bukti</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->paid_at ? $order->paid_at->format('d M Y H:i') : 'Belum diunggah' }}</dd>
                    </div>
                    @if ($order->payment_note)
                        <div class="flex justify-between gap-4">
                            <dt class="text-[#AAB2C5]">Catatan Pembeli</dt>
                            <dd class="text-right font-semibold text-[#F8FAFC]">{{ $order->payment_note }}</dd>
                        </div>
                    @endif
                    @if ($order->verified_at)
                        <div class="flex justify-between gap-4 border-t border-[#30364D] pt-2.5">
                            <dt class="text-[#AAB2C5]">Diverifikasi</dt>
                            <dd class="text-right font-semibold text-[#22C55E]">
                                {{ $order->verified_at->format('d M Y H:i') }}{{ $order->verifier ? ' oleh '.$order->verifier->name : '' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Tujuan Pembayaran Modder</h2>
                <dl class="mt-4 space-y-2.5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Toko Modder</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Metode</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->payment_method_snapshot['provider_name'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Nomor Rekening / Tujuan</dt>
                        <dd class="font-bold text-[#A78BFA]">{{ $order->payment_method_snapshot['account_number'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $order->payment_method_snapshot['account_holder'] ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-[#30364D] pt-3">
                        <dt class="text-[#AAB2C5]">Harga Mod</dt>
                        <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-[#AAB2C5]">Biaya Admin Platform</dt>
                        <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-[#30364D] pt-3 text-base font-extrabold">
                        <dt class="text-[#F8FAFC]">Total Transfer</dt>
                        <dd class="text-[#22C55E]">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
                @if (! empty($order->payment_method_snapshot['instructions']))
                    <p class="mt-4 rounded-xl border border-[#30364D] bg-[#1A1E30] p-3 text-xs text-[#AAB2C5]">
                        {{ $order->payment_method_snapshot['instructions'] }}
                    </p>
                @endif
            </div>

            @if ($canVerify)
                <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-4">
                    <label for="seller-note" class="sr-only">Catatan Modder</label>
                    <textarea
                        id="seller-note"
                        rows="3"
                        wire:model.live.debounce.600ms="sellerNote"
                        placeholder="Tulis catatan untuk pembeli atau pesanan ini..."
                        class="w-full rounded-xl border border-[#30364D] bg-[#1A1E30] p-3 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60 focus:border-[#7C4DFF]"
                    ></textarea>
                    @error('sellerNote') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                </div>
            @elseif ($order->seller_note)
                <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-4">
                    <p class="whitespace-pre-line rounded-xl border border-[#30364D] bg-[#1A1E30] p-3 text-sm leading-relaxed text-[#F8FAFC]">{{ $order->seller_note }}</p>
                </div>
            @endif

            @if ($order->status->value === 'rejected' && $order->rejection_reason)
                <div role="alert" class="rounded-2xl border border-[#F87171]/40 bg-[#F87171]/15 p-5 text-sm text-[#F87171]">
                    <p class="font-bold">Bukti pembayaran sebelumnya ditolak:</p>
                    <p class="mt-1">{{ $order->rejection_reason }}</p>
                </div>
            @endif

            @if ($order->buyer_id === auth()->id() && $order->canUploadProof())
                <form wire:submit="submitProof" class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                    <h2 class="text-base font-bold text-[#F8FAFC]">Unggah Bukti Pembayaran</h2>
                    <p class="mt-1 text-xs text-[#AAB2C5]">Format JPG, PNG, atau WEBP maksimal 4 MB.</p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="proof-file" class="block text-sm font-semibold text-[#F8FAFC]">File Bukti Transfer</label>
                            <input
                                id="proof-file"
                                type="file"
                                wire:model="paymentProof"
                                accept="image/png,image/jpeg,image/webp"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2.5 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white"
                            >
                            @error('paymentProof') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="proof-note" class="block text-sm font-semibold text-[#F8FAFC]">Catatan (Opsional)</label>
                            <input
                                id="proof-note"
                                type="text"
                                wire:model="paymentNote"
                                placeholder="Nama pengirim atau waktu transfer"
                                class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC] placeholder-[#AAB2C5]/60"
                            >
                            @error('paymentNote') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white hover:bg-[#6D3DF5] disabled:opacity-60"
                        >
                            <span wire:loading.remove wire:target="submitProof">Kirim Bukti Pembayaran</span>
                            <span wire:loading wire:target="submitProof">Mengunggah bukti...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <div class="space-y-5 lg:col-span-5">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Item Mod dalam Pesanan</h2>

                <div class="mt-4 space-y-3">
                    @foreach ($order->items as $item)
                        <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $item->product_name_snapshot }}</p>
                            <p class="mt-1 text-xs text-[#AAB2C5]">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</p>

                            @if ($order->isCompleted() && $order->buyer_id === auth()->id() && isset($downloadLinks[$item->id]))
                                <a
                                    href="{{ $downloadLinks[$item->id] }}"
                                    class="mt-3 inline-flex min-h-[44px] w-full items-center justify-center rounded-xl bg-[#22C55E] px-4 text-sm font-bold text-[#0B0D17] hover:bg-[#16A34A]"
                                >
                                    Unduh File Mod (Tautan Aman)
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <a href="{{ $backRoute }}" class="mt-5 inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-[#30364D] px-4 text-sm font-semibold text-[#F8FAFC] hover:bg-[#1A1E30]">
                    Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
