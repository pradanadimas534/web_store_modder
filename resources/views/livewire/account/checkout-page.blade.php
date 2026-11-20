<div class="mx-auto max-w-[980px] px-4 py-8 lg:px-8">
    <h1 class="text-2xl font-extrabold text-[#F8FAFC] sm:text-3xl">Checkout Mod Berbayar</h1>
    <p class="mt-1 text-sm text-[#AAB2C5]">Pilih metode pembayaran milik modder, buat pesanan, lalu unggah bukti transfer untuk verifikasi.</p>

    <div class="mt-6 grid gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-7">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Pilih Metode Pembayaran Modder</h2>
                <p class="mt-1 text-xs text-[#AAB2C5]">
                    Pembayaran ditujukan langsung ke rekening yang diatur oleh {{ $product->seller->sellerProfile?->store_name ?? $product->seller->name }}.
                </p>

                @if ($paymentMethods->isEmpty())
                    <div class="mt-4 rounded-xl border border-[#F87171]/40 bg-[#F87171]/15 p-4 text-sm text-[#F87171]">
                        Modder belum mengatur metode pembayaran aktif. Transaksi tidak dapat dilanjutkan saat ini.
                    </div>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($paymentMethods as $method)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors {{ $selectedPaymentMethodId === $method->id ? 'border-[#7C4DFF] bg-[#1A1E30]' : 'border-[#30364D] bg-[#0B0D17]/60 hover:border-[#7C4DFF]/50' }}">
                                <input
                                    type="radio"
                                    name="payment_method"
                                    wire:model.live="selectedPaymentMethodId"
                                    value="{{ $method->id }}"
                                    class="mt-1 h-4 w-4 text-[#7C4DFF]"
                                >
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-[#F8FAFC]">{{ $method->provider_name }}</p>
                                    <p class="mt-0.5 text-sm font-semibold text-[#A78BFA]">{{ $method->account_number }}</p>
                                    <p class="text-xs text-[#AAB2C5]">a.n. {{ $method->account_holder }}</p>
                                    @if ($method->instructions)
                                        <p class="mt-2 text-xs text-[#AAB2C5]">{{ $method->instructions }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPaymentMethodId')
                        <p class="mt-2 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>

        <div class="lg:col-span-5">
            <form wire:submit="createOrder" class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-base font-bold text-[#F8FAFC]">Ringkasan Pesanan</h2>

                <div class="mt-4 rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                    <p class="text-xs font-semibold text-[#38BDF8]">{{ $product->game?->short_code }} &middot; {{ $product->category?->name }}</p>
                    <p class="mt-1 text-sm font-bold text-[#F8FAFC]">{{ $product->name }}</p>
                    <p class="mt-1 text-xs text-[#AAB2C5]">Versi {{ $product->mod_version }} ({{ $product->formattedFileSize() }})</p>
                </div>

                <dl class="mt-4 space-y-2 border-t border-[#30364D] pt-4 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-[#AAB2C5]">Harga Mod</dt>
                        <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-[#AAB2C5]">
                            Biaya Admin Platform
                            @if ($feePercent > 0 || $feeFixed > 0)
                                <span class="text-xs">({{ rtrim(rtrim(number_format($feePercent, 2, '.', ''), '0'), '.') }}% + Rp {{ number_format($feeFixed, 0, ',', '.') }})</span>
                            @endif
                        </dt>
                        <dd class="font-semibold text-[#F8FAFC]">Rp {{ number_format($adminFee, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-[#30364D] pt-2 text-base font-extrabold">
                        <dt class="text-[#F8FAFC]">Total Bayar</dt>
                        <dd class="text-[#A78BFA]">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <label class="mt-5 flex cursor-pointer items-start gap-2.5 text-xs leading-relaxed text-[#AAB2C5]">
                    <input type="checkbox" wire:model="agreeTerms" class="mt-0.5 h-4 w-4 rounded border-[#30364D] bg-[#1A1E30] text-[#7C4DFF]">
                    <span>Saya memahami bahwa akses unduhan mod digital dibuka setelah pembayaran diverifikasi oleh modder.</span>
                </label>
                @error('agreeTerms')
                    <p class="mt-1.5 text-xs font-medium text-[#F87171]">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    @disabled($paymentMethods->isEmpty())
                    class="mt-5 inline-flex min-h-[46px] w-full items-center justify-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5] disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="createOrder">Buat Pesanan & Bayar</span>
                    <span wire:loading wire:target="createOrder">Memproses pesanan...</span>
                </button>
            </form>
        </div>
    </div>
</div>
