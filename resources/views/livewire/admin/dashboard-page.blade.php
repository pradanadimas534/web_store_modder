<div class="mx-auto max-w-[1280px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-[#AAB2C5]">Ringkasan operasional aktual ModGame.id termasuk penghasilan biaya admin transaksi dan periklanan.</p>
    </div>

    <x-admin-nav />

    <section aria-labelledby="platform-income-heading" class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <h2 id="platform-income-heading" class="sr-only">Penghasilan Operasional Platform</h2>
        <div class="rounded-2xl border border-[#7C4DFF]/50 bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#A78BFA]">Kas Operasional Diterima</p>
            <p class="mt-2 text-2xl font-extrabold text-[#22C55E]">Rp {{ number_format($totalPlatformRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">Setoran modder lunas + iklan lunas</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Setoran Admin Mod (Lunas)</p>
            <p class="mt-2 text-2xl font-extrabold text-[#F8FAFC]">Rp {{ number_format($settledOrderFeeRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $pendingSettlementsCount }} setoran menunggu verifikasi</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Piutang Admin di Modder</p>
            <p class="mt-2 text-2xl font-extrabold text-[#FBBF24]">Rp {{ number_format($unsettledOrderFeeReceivable, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">Belum disetorkan modder</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Pendapatan Iklan (Lunas)</p>
            <p class="mt-2 text-2xl font-extrabold text-[#38BDF8]">Rp {{ number_format($platformAdRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $activeAdsCount }} iklan sedang tayang</p>
        </div>
    </section>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Total Nilai Transaksi</p>
            <p class="mt-2 text-xl font-extrabold text-[#F8FAFC]">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $completedOrdersCount }} order terverifikasi</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Moderasi Mod</p>
            <p class="mt-2 text-xl font-extrabold text-[#FBBF24]">{{ $pendingProductsCount }} menunggu</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $activeProductsCount }} mod aktif</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Pengguna Terdaftar</p>
            <p class="mt-2 text-xl font-extrabold text-[#F8FAFC]">{{ $sellersCount }} modder</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $buyersCount }} pembeli</p>
        </div>
        <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
            <p class="text-xs font-semibold text-[#AAB2C5]">Antrean Review Iklan</p>
            <p class="mt-2 text-xl font-extrabold text-[#38BDF8]">{{ $pendingAdsCount }} menunggu</p>
            <p class="mt-1 text-xs text-[#AAB2C5]">{{ $activeAdsCount }} iklan tayang</p>
        </div>
    </div>

    <section class="mt-8 rounded-2xl border border-[#30364D] bg-[#121525] p-6">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Transaksi Terbaru</h2>
            <a href="{{ route('admin.orders') }}" class="text-sm font-semibold text-[#A78BFA] hover:underline">Lihat semua</a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="mt-4 text-sm text-[#AAB2C5]">Belum ada transaksi di sistem.</p>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                        <div>
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#AAB2C5]">
                                {{ $order->buyer->name }} &rarr; {{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}
                                &middot; Total Rp {{ number_format($order->total, 0, ',', '.') }}
                                (Admin: Rp {{ number_format($order->admin_fee, 0, ',', '.') }})
                            </p>
                        </div>
                        <x-status-badge :status="$order->status" />
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
