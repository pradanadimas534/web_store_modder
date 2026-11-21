<div class="mx-auto max-w-[1360px] px-4 py-8 lg:px-8">
    <div class="mb-4">
        <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-[#A0AAA2]">Ringkasan operasional aktual ModGame.id termasuk penghasilan biaya admin transaksi dan periklanan.</p>
    </div>

    <x-admin-nav />

    <section aria-labelledby="platform-income-heading" class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <h2 id="platform-income-heading" class="sr-only">Penghasilan Operasional Platform</h2>
        <div class="rounded-2xl border border-[#7CFF00]/50 bg-[#101614] p-5">
            <p class="text-xs font-bold text-[#7CFF00]">Kas Operasional Diterima</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#7CFF00]">Rp {{ number_format($totalPlatformRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">Setoran modder lunas + iklan lunas</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Setoran Admin Mod (Lunas)</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#F2F5F0]">Rp {{ number_format($settledOrderFeeRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $pendingSettlementsCount }} setoran menunggu verifikasi</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Piutang Admin di Modder</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#F5C451]">Rp {{ number_format($unsettledOrderFeeReceivable, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">Belum disetorkan modder</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Pendapatan Iklan (Lunas)</p>
            <p class="font-display mt-2 text-2xl font-extrabold text-[#35D07F]">Rp {{ number_format($platformAdRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $activeAdsCount }} iklan sedang tayang</p>
        </div>
    </section>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Total Nilai Transaksi</p>
            <p class="font-display mt-2 text-xl font-extrabold text-[#F2F5F0]">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $completedOrdersCount }} order terverifikasi</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Moderasi Mod</p>
            <p class="font-display mt-2 text-xl font-extrabold text-[#F5C451]">{{ $pendingProductsCount }} menunggu</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $activeProductsCount }} mod aktif</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Pengguna Terdaftar</p>
            <p class="font-display mt-2 text-xl font-extrabold text-[#F2F5F0]">{{ $sellersCount }} modder</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $buyersCount }} pembeli</p>
        </div>
        <div class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
            <p class="text-xs font-semibold text-[#A0AAA2]">Antrean Review Iklan</p>
            <p class="font-display mt-2 text-xl font-extrabold text-[#B6E879]">{{ $pendingAdsCount }} menunggu</p>
            <p class="mt-1 text-xs text-[#A0AAA2]">{{ $activeAdsCount }} iklan tayang</p>
        </div>
    </div>

    <section class="mt-8 rounded-2xl border border-[#29342D] bg-[#101614] p-6">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Transaksi Terbaru</h2>
            <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-[#7CFF00] hover:underline">Lihat semua</a>
        </div>

        @if ($recentOrders->isEmpty())
            <p class="mt-4 text-sm text-[#A0AAA2]">Belum ada transaksi di sistem.</p>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#29342D] bg-[#151D19] p-4">
                        <div>
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#A0AAA2]">
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
