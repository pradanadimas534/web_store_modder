<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Dashboard Modder</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Kelola katalog mod, metode pembayaran toko, dan verifikasi pesanan pembeli.</p>
    </div>

    <x-seller-nav />

    @if ($activePaymentMethodsCount === 0)
        <div role="alert" class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#FBBF24]/40 bg-[#FBBF24]/15 p-4 text-sm text-[#FBBF24]">
            <span>Anda belum memiliki metode pembayaran aktif. Pembeli tidak dapat membeli mod berbayar sebelum metode pembayaran diatur.</span>
            <a href="{{ route('seller.payments') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#FBBF24] px-4 text-xs font-bold text-[#0B0D17]">
                Atur Pembayaran
            </a>
        </div>
    @endif

    @if ($unsettledAdminFee > 0)
        <div role="alert" class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border {{ $isOverFeeLimit ? 'border-[#F87171]/50 bg-[#F87171]/15 text-[#F87171]' : 'border-[#FBBF24]/40 bg-[#FBBF24]/15 text-[#FBBF24]' }} p-4 text-sm">
            <span>
                @if ($isOverFeeLimit)
                    Tagihan biaya admin Anda sebesar Rp {{ number_format($unsettledAdminFee, 0, ',', '.') }} telah mencapai batas maksimal. Checkout mod berbayar sementara dijeda hingga setoran diverifikasi.
                @else
                    Anda memiliki tagihan biaya admin platform sebesar Rp {{ number_format($unsettledAdminFee, 0, ',', '.') }} yang perlu disetorkan ke Admin.
                @endif
            </span>
            <a href="{{ route('seller.settlements') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-4 text-xs font-bold text-white">
                Setor Biaya Admin
            </a>
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Mod Aktif</p>
            <p class="mt-2 text-2xl font-extrabold text-[#F8FAFC]">{{ $activeProductsCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Menunggu Review Admin</p>
            <p class="mt-2 text-2xl font-extrabold text-[#FBBF24]">{{ $pendingProductsCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Pesanan Perlu Verifikasi</p>
            <p class="mt-2 text-2xl font-extrabold text-[#38BDF8]">{{ $pendingOrdersCount }}</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Pendapatan Terverifikasi</p>
            <p class="mt-2 text-2xl font-extrabold text-[#22C55E]">Rp {{ number_format($completedRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="mt-8 rounded-2xl border border-[#30364D] bg-[#121525] p-6">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Pesanan Terbaru</h2>
            <a href="{{ route('seller.orders') }}" class="text-sm font-semibold text-[#A78BFA] hover:underline">Lihat semua</a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="mt-4 text-sm text-[#AAB2C5]">Belum ada pesanan masuk untuk toko Anda.</p>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                        <div>
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#AAB2C5]">Pembeli: {{ $order->buyer->name }} &middot; Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                        <x-status-badge :status="$order->status" />
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
