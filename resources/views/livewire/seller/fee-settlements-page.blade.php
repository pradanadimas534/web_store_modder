<div class="mx-auto max-w-[1240px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Setoran Biaya Admin Platform</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Bayar akumulasi biaya admin transaksi secara langsung ke akun pembayaran Admin ModGame.id tanpa perlu mengunggah bukti manual.</p>
    </div>

    <x-seller-nav />

    @if ($isOverLimit)
        <div role="alert" class="mb-6 rounded-2xl border border-[#FF6262]/50 bg-[#FF6262]/15 p-4 text-sm text-[#FF6262]">
            Tagihan biaya admin Anda telah mencapai batas maksimal (Rp {{ number_format($setting->max_unpaid_admin_fee, 0, ',', '.') }}). Checkout mod berbayar di toko Anda sementara dijeda hingga Anda melakukan pembayaran setoran di bawah ini.
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Tagihan Belum Disetor</p>
            <p class="font-display mt-2 text-2xl font-extrabold {{ $unsettledTotal > 0 ? 'text-[#F5C451]' : 'text-[#F2F5F0]' }}">
                Rp {{ number_format($unsettledTotal, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-[#A0AAA2]">Dari {{ $unsettledOrders->count() }} transaksi selesai</p>
        </div>

        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Batas Maksimal Tunggakan</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#F2F5F0]">
                {{ $setting->max_unpaid_admin_fee > 0 ? 'Rp '.number_format($setting->max_unpaid_admin_fee, 0, ',', '.') : 'Tanpa Batas' }}
            </p>
            <p class="mt-1 text-xs text-[#A0AAA2]">Checkout mod berbayar dijeda bila mencapai batas ini</p>
        </div>

        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Total Sudah Disetor (Lunas)</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#7CFF00]">Rp {{ number_format($verifiedTotal, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">Masuk ke akun Admin</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-5">
            <div class="rounded-2xl border border-[#7CFF00]/40 bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Akun Pembayaran Admin</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Bank / E-Wallet</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_provider }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Nomor Rekening</dt>
                        <dd class="font-extrabold text-[#7CFF00]">{{ $setting->admin_payment_account_number }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-[#A0AAA2]">Atas Nama</dt>
                        <dd class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_account_holder }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 border-t border-[#29342D] pt-2.5 text-base font-extrabold">
                        <dt class="text-[#F2F5F0]">Total Tagihan Setoran</dt>
                        <dd class="font-display text-[#7CFF00]">Rp {{ number_format($unsettledTotal, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                @if ($unsettledTotal > 0)
                    <button
                        type="button"
                        wire:click="openPaymentGateway"
                        class="mt-5 inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B]"
                    >
                        Bayar Setoran ke Akun Admin
                    </button>
                @else
                    <div class="mt-5 rounded-xl border border-[#35D07F]/40 bg-[#35D07F]/10 p-4 text-xs font-semibold text-[#35D07F]">
                        Semua tagihan biaya admin Anda saat ini sudah lunas terbayar.
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-5 lg:col-span-7">
            <section class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Rincian Transaksi Belum Disetor</h2>
                @if ($unsettledOrders->isEmpty())
                    <p class="mt-3 text-sm text-[#A0AAA2]">Tidak ada transaksi selesai yang belum disetor biaya adminnya.</p>
                @else
                    <div class="mt-4 space-y-2.5">
                        @foreach ($unsettledOrders as $order)
                            <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-[#29342D] bg-[#151D19] px-4 py-3 text-xs">
                                <div>
                                    <p class="font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                                    <p class="text-[#A0AAA2]">{{ $order->verified_at?->format('d M Y H:i') ?? $order->updated_at->format('d M Y H:i') }}</p>
                                </div>
                                <p class="font-bold text-[#F5C451]">Biaya Admin: Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Riwayat Setoran Biaya Admin</h2>
                @if ($settlements->isEmpty())
                    <p class="mt-3 text-sm text-[#A0AAA2]">Belum ada riwayat setoran.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($settlements as $item)
                            <div class="rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $item->settlement_number }}</p>
                                        <p class="text-xs text-[#A0AAA2]">
                                            {{ $item->orders_count }} order &middot; Rp {{ number_format($item->amount, 0, ',', '.') }}
                                            &middot; Dibayar {{ $item->submitted_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <x-status-badge :status="$item->status" />
                                </div>
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

    @if ($showPaymentGateway)
        <div
            style="position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background-color: rgba(8, 11, 10, 0.85); backdrop-filter: blur(4px); padding: 1rem;"
            class="fixed inset-0 z-50 flex items-center justify-center px-4"
            x-data
            x-on:keydown.escape.window="$wire.closePaymentGateway()"
            wire:click.self="closePaymentGateway"
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-labelledby="settlement-pay-title"
                style="width: 100%; max-width: 28rem; background-color: #101614; border: 1px solid #29342D; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75);"
                class="w-full max-w-md rounded-2xl border border-[#29342D] bg-[#101614] p-6 shadow-2xl"
            >
                <div class="flex items-center justify-between gap-3 border-b border-[#29342D] pb-3">
                    <h2 id="settlement-pay-title" class="font-display text-base font-extrabold text-[#F2F5F0]">Pembayaran Setoran ke Akun Admin</h2>
                    <span class="rounded-lg border border-[#7CFF00]/40 bg-[#7CFF00]/15 px-2.5 py-0.5 text-xs font-bold text-[#7CFF00]">Direct Pay</span>
                </div>

                <div class="mt-4 rounded-xl border border-[#29342D] bg-[#151D19] p-4 text-xs space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-[#A0AAA2]">Tujuan Akun</span>
                        <span class="font-bold text-[#F2F5F0]">{{ $setting->admin_payment_provider }} &middot; {{ $setting->admin_payment_account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#A0AAA2]">Penerima</span>
                        <span class="font-semibold text-[#F2F5F0]">{{ $setting->admin_payment_account_holder }}</span>
                    </div>
                    <div class="flex justify-between border-t border-[#29342D] pt-2 text-sm font-extrabold">
                        <span class="text-[#F2F5F0]">Tagihan Harus Dibayar</span>
                        <span class="font-display text-[#7CFF00]">Rp {{ number_format($unsettledTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit="paySettlement" class="mt-4 space-y-4">
                    <div>
                        <label for="settlement-amount" class="block text-xs font-semibold text-[#F2F5F0]">Masukkan Nominal Pembayaran (Rp)</label>
                        <input
                            id="settlement-amount"
                            type="number"
                            wire:model="paymentAmount"
                            autofocus
                            placeholder="{{ $unsettledTotal }}"
                            required
                            class="mt-1.5 min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm font-bold text-[#F2F5F0] placeholder-[#A0AAA2]/50 focus:border-[#7CFF00]"
                        >
                        @error('paymentAmount')
                            <p class="mt-1 text-xs font-semibold text-[#FF6262]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 border-t border-[#29342D] pt-4">
                        <button
                            type="button"
                            wire:click="closePaymentGateway"
                            style="background-color: #1C2721; color: #F2F5F0; border: 1px solid #29342D;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#29342D] bg-[#1C2721] px-4 text-xs font-bold text-[#F2F5F0]"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            style="background-color: #7CFF00; color: #080B0A;"
                            class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B] disabled:opacity-60"
                        >
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
