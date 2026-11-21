<div class="mx-auto max-w-[980px] px-4 py-8 lg:px-8">
    <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0] sm:text-3xl">Checkout Mod Berbayar</h1>
    <p class="mt-1 text-sm text-[#A0AAA2]">Pilih metode pembayaran milik modder, buat pesanan, lalu unggah bukti transfer untuk verifikasi.</p>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-7">
            <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Pilih Metode Pembayaran Modder</h2>
                <p class="mt-1 text-xs text-[#A0AAA2]">
                    Pembayaran ditujukan langsung ke rekening yang diatur oleh {{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}.
                </p>

                @if ($paymentMethods->isEmpty())
                    <div class="mt-4 rounded-xl border border-[#FF6262]/40 bg-[#FF6262]/15 p-4 text-sm text-[#FF6262]">
                        Modder belum mengatur metode pembayaran aktif. Transaksi tidak dapat dilanjutkan saat ini.
                    </div>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($paymentMethods as $method)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors {{ $selectedPaymentMethodId === $method->id ? 'border-[#7CFF00] bg-[#151D19]' : 'border-[#29342D] bg-[#080B0A]/60 hover:border-[#7CFF00]/50' }}">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    wire:model.live="selectedPaymentMethodId"
                                    value="{{ $method->id }}"
                                    class="mt-1 h-4 w-4 accent-[#7CFF00]"
                                >
                                <div class="min-w-0 flex-1">
                                    <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $method->provider_name }}</p>
                                    <p class="mt-0.5 text-sm font-extrabold text-[#7CFF00]">{{ $method->account_number }}</p>
                                    <p class="text-xs text-[#A0AAA2]">a.n. {{ $method->account_holder }}</p>
                                    @if ($method->instructions)
                                        <p class="mt-2 text-xs text-[#A0AAA2]">{{ $method->instructions }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPaymentMethodId')
                        <p class="mt-2 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>

        <div class="lg:col-span-5">
            <form wire:submit="createOrder" class="rounded-2xl border border-[#29342D] bg-[#101614] p-6">
                <h2 class="font-display text-base font-bold text-[#F2F5F0]">Ringkasan Pembayaran</h2>

                <div class="mt-4 rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                    <p class="text-xs font-bold text-[#7CFF00]">{{ $product->game?->short_code }} &middot; {{ $product->category?->name }}</p>
                    <p class="font-display mt-1 text-sm font-bold text-[#F2F5F0]">{{ $product->name }}</p>
                    <p class="mt-1 text-xs text-[#A0AAA2]">Versi {{ $product->mod_version }} ({{ $product->formattedFileSize() }})</p>
                </div>

                <dl class="mt-4 space-y-2 border-t border-[#29342D] pt-4 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#A0AAA2]">Harga Mod</dt>
                        <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#A0AAA2]">
                            Biaya Admin Platform
                            @if ($feePercent > 0 || $feeFixed > 0)
                                <span class="text-xs">({{ rtrim(rtrim(number_format($feePercent, 2, '.', ''), '0'), '.') }}% + Rp {{ number_format($feeFixed, 0, ',', '.') }})</span>
                            @endif
                        </dt>
                        <dd class="font-semibold text-[#F2F5F0]">Rp {{ number_format($adminFee, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-[#29342D] pt-2 text-base font-extrabold">
                        <dt class="text-[#F2F5F0]">Total Bayar</dt>
                        <dd class="font-display text-[#7CFF00]">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <label class="mt-5 flex cursor-pointer items-start gap-2.5 text-xs leading-relaxed text-[#A0AAA2]">
                    <input type="checkbox" wire:model="agreeTerms" class="mt-0.5 h-4 w-4 rounded border-[#29342D] bg-[#151D19] accent-[#7CFF00]">
                    <span>Saya memahami bahwa akses unduhan mod digital dibuka setelah pembayaran diverifikasi oleh modder.</span>
                </label>
                @error('agreeTerms')
                    <p class="mt-1.5 text-xs font-medium text-[#FF6262]">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    @disabled($paymentMethods->isEmpty())
                    class="mt-5 inline-flex min-h-[46px] w-full items-center justify-center rounded-[10px] bg-[#7CFF00] px-4 text-sm font-extrabold text-[#080B0A] shadow-[0_0_24px_rgba(124,255,0,0.22)] transition-colors hover:bg-[#9BFF3B] disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="createOrder">Bayar Sekarang</span>
                    <span wire:loading wire:target="createOrder">Memproses pesanan...</span>
                </button>
            </form>
        </div>
    </div>
</div>
