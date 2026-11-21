<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Dashboard Modder</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Kelola katalog mod, metode pembayaran toko, dan verifikasi pesanan pembeli.</p>
    </div>

    <x-seller-nav />

    @if ($activePaymentMethodsCount === 0)
        <div role="alert" class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#F5C451]/40 bg-[#F5C451]/15 p-4 text-sm text-[#F5C451]">
            <span>Anda belum memiliki metode pembayaran aktif. Pembeli tidak dapat membeli mod berbayar sebelum metode pembayaran diatur.</span>
            <a href="{{ route('seller.payments') }}" class="inline-flex min-h-[40px] items-center rounded-[10px] bg-[#F5C451] px-4 text-xs font-extrabold text-[#080B0A]">
                Atur Pembayaran
            </a>
        </div>
    @endif

    @if ($unsettledAdminFee > 0)
        <div role="alert" class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border {{ $isOverFeeLimit ? 'border-[#FF6262]/50 bg-[#FF6262]/15 text-[#FF6262]' : 'border-[#F5C451]/40 bg-[#F5C451]/15 text-[#F5C451]' }} p-4 text-sm">
            <span>
                @if ($isOverFeeLimit)
                    Tagihan biaya admin Anda sebesar Rp {{ number_format($unsettledAdminFee, 0, ',', '.') }} telah mencapai batas maksimal. Checkout mod berbayar sementara dijeda hingga setoran diverifikasi.
                @else
                    Anda memiliki tagihan biaya admin platform sebesar Rp {{ number_format($unsettledAdminFee, 0, ',', '.') }} yang perlu disetorkan ke Admin.
                @endif
            </span>
            <a href="{{ route('seller.settlements') }}" class="inline-flex min-h-[40px] items-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A]">
                Setor Biaya Admin
            </a>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Mod Aktif</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#F2F5F0]">{{ $activeProductsCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Menunggu Review Admin</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#F5C451]">{{ $pendingProductsCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Pesanan Perlu Verifikasi</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#B6E879]">{{ $pendingOrdersCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Pendapatan Terverifikasi</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#7CFF00]">Rp {{ number_format($completedRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="mt-8 rounded-2xl border border-[#29342D] bg-[#101614] p-6">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Pesanan Terbaru</h2>
            <a href="{{ route('seller.orders') }}" class="text-xs font-bold text-[#7CFF00] hover:underline">Lihat semua</a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="mt-4 text-sm text-[#A0AAA2]">Belum ada pesanan masuk untuk toko Anda.</p>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                        <div>
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#A0AAA2]">Pembeli: {{ $order->buyer->name }} &middot; Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                        <x-status-badge :status="$order->status" />
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
