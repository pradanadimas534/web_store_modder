<div class="mx-auto max-w-[1200px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Setoran Biaya Admin Platform</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Karena pembayaran pembeli masuk langsung ke rekening Anda, setorkan akumulasi biaya admin transaksi ke rekening Admin ModGame.id di sini.</p>
    </div>

    <x-seller-nav />

    @if ($isOverLimit)
        <div role="alert" class="mb-6 rounded-2xl border border-[#F87171]/50 bg-[#F87171]/15 p-4 text-sm text-[#F87171]">
            Tagihan biaya admin Anda telah mencapai batas maksimal (Rp {{ number_format($setting->max_unpaid_admin_fee, 0, ',', '.') }}). Checkout mod berbayar di toko Anda sementara dijeda hingga setoran diverifikasi admin.
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Tagihan Belum Disetor</p>
            <p class="mt-2 text-2xl font-extrabold {{ $unsettledTotal > 0 ? 'text-[#FBBF24]' : 'text-[#F8FAFC]' }}">
                Rp {{ number_format($unsettledTotal, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-[#AAB2C5]">Dari {{ $unsettledOrders->count() }} transaksi selesai</p>
        </div>

        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Batas Maksimal Tunggakan</p>
            <p class="mt-2 text-2xl font-extrabold text-[#F8FAFC]">
                {{ $setting->max_unpaid_admin_fee > 0 ? 'Rp '.number_format($setting->max_unpaid_admin_fee, 0, ',', '.') : 'Tanpa Batas' }}
            </p>
            <p class="mt-1 text-xs text-[#AAB2C5]">Checkout mod berbayar dijeda bila mencapai batas ini</p>
        </div>

        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Total Sudah Disetor (Lunas)</p>
            <p class="mt-2 text-2xl font-extrabold text-[#22C55E]">Rp {{ number_format($verifiedTotal, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">Terverifikasi oleh admin</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-5">
            <div class="rounded-2xl border border-[#7C4DFF]/50 bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Rekening Tujuan Setoran (Admin)</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Bank / E-Wallet</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $setting->admin_payment_provider }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Nomor Rekening</dt>
                        <dd class="font-bold text-[#A78BFA]">{{ $setting->admin_payment_account_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#AAB2C5]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F8FAFC]">{{ $setting->admin_payment_account_holder }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 border-t border-[#30364D] pt-2.5 text-base font-extrabold">
                        <dt class="text-[#F8FAFC]">Nominal Setoran</dt>
                        <dd class="text-[#22C55E]">Rp {{ number_format($unsettledTotal, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            @if ($unsettledTotal > 0)
                <form wire:submit="submitSettlement" class="rounded-2xl border border-[#30364D] bg-[#121525] p-6 space-y-4">
                    <h2 class="text-base font-bold text-[#F8FAFC]">Kirim Bukti Setoran Biaya Admin</h2>

                    <div>
                        <label for="settlement-proof" class="block text-sm font-semibold text-[#F8FAFC]">Bukti Transfer (Wajib, JPG/PNG/WEBP, maks 4 MB)</label>
                        <input
                            id="settlement-proof"
                            type="file"
                            wire:model="paymentProof"
                            accept="image/png,image/jpeg,image/webp"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 py-2 text-xs text-[#AAB2C5] file:mr-3 file:rounded-lg file:border-0 file:bg-[#7C4DFF] file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white"
                        >
                        @error('paymentProof') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="settlement-note" class="block text-sm font-semibold text-[#F8FAFC]">Catatan Transfer (Opsional)</label>
                        <input
                            id="settlement-note"
                            type="text"
                            wire:model="paymentNote"
                            placeholder="Nama pengirim atau waktu transfer"
                            class="mt-1.5 min-h-[44px] w-full rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm text-[#F8FAFC]"
                        >
                        @error('paymentNote') <p class="mt-1 text-xs text-[#F87171]">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white hover:bg-[#6D3DF5] disabled:opacity-60">
                        <span wire:loading.remove wire:target="submitSettlement">Kirim Bukti Setoran</span>
                        <span wire:loading wire:target="submitSettlement">Mengunggah bukti...</span>
                    </button>
                </form>
            @else
                <div class="rounded-2xl border border-[#22C55E]/40 bg-[#22C55E]/10 p-5 text-sm text-[#22C55E]">
                    Semua tagihan biaya admin Anda saat ini sudah lunas atau sedang menunggu verifikasi admin.
                </div>
            @endif
        </div>

        <div class="space-y-5 lg:col-span-7">
            <section class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Rincian Transaksi Belum Disetor</h2>
                @if ($unsettledOrders->isEmpty())
                    <p class="mt-3 text-sm text-[#AAB2C5]">Tidak ada transaksi selesai yang belum disetor biaya adminnya.</p>
                @else
                    <div class="mt-4 space-y-2.5">
                        @foreach ($unsettledOrders as $order)
                            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 py-3 text-xs">
                                <div>
                                    <p class="font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                                    <p class="text-[#AAB2C5]">{{ $order->verified_at?->format('d M Y H:i') ?? $order->updated_at->format('d M Y H:i') }}</p>
                                </div>
                                <p class="font-bold text-[#FBBF24]">Biaya Admin: Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Riwayat Setoran Biaya Admin</h2>
                @if ($settlements->isEmpty())
                    <p class="mt-3 text-sm text-[#AAB2C5]">Belum ada riwayat setoran.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($settlements as $item)
                            <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-bold text-[#F8FAFC]">{{ $item->settlement_number }}</p>
                                        <p class="text-xs text-[#AAB2C5]">
                                            {{ $item->orders_count }} order &middot; Rp {{ number_format($item->amount, 0, ',', '.') }}
                                            &middot; Dikirim {{ $item->submitted_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <x-status-badge :status="$item->status" />
                                </div>
                                @if ($item->admin_note)
                                    <p class="mt-2 text-xs text-[#F87171]">Alasan penolakan: {{ $item->admin_note }}</p>
                                @endif
                                <a href="{{ route('settlements.proof', $item) }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-[38px] items-center rounded-lg border border-[#30364D] bg-[#121525] px-3 text-xs font-semibold text-[#A78BFA]">
                                    Lihat Bukti Transfer
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $settlements->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
