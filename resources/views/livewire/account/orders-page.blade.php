<div class="mx-auto max-w-[1200px] px-4 py-8 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-[#30364D] pb-5">
        <div>
            <h1 class="text-2xl font-extrabold text-[#F8FAFC]">Pesanan & Unduhan Saya</h1>
            <p class="mt-1 text-sm text-[#AAB2C5]">Pantau status verifikasi pembayaran dan unduh mod berbayar yang telah selesai.</p>
        </div>
        <a href="{{ route('catalog') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-semibold text-white hover:bg-[#6D3DF5]">
            Jelajahi Katalog
        </a>
    </div>

    @if ($orders->isEmpty())
        <div class="mt-8 rounded-2xl border border-[#30364D] bg-[#121525] p-10 text-center">
            <h2 class="text-lg font-bold text-[#F8FAFC]">Belum ada transaksi mod berbayar</h2>
            <p class="mt-2 text-sm text-[#AAB2C5]">Pesanan mod berbayar yang Anda buat akan muncul di sini beserta tautan unduhan setelah terverifikasi.</p>
            <a href="{{ route('catalog') }}" class="mt-5 inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-5 text-sm font-semibold text-white">
                Cari Mod di Katalog
            </a>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($orders as $order)
                <article class="rounded-2xl border border-[#30364D] bg-[#121525] p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#30364D] pb-4">
                        <div>
                            <p class="text-sm font-bold text-[#F8FAFC]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#AAB2C5]">
                                {{ $order->created_at->format('d M Y H:i') }} &middot; Modder: {{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-[#F8FAFC]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div class="space-y-1">
                            @foreach ($order->items as $item)
                                <p class="text-sm font-semibold text-[#F8FAFC]">{{ $item->product_name_snapshot }}</p>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('orders.payment', $order->order_number) }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-sm font-semibold text-[#F8FAFC] hover:border-[#7C4DFF]">
                                {{ $order->canUploadProof() ? 'Bayar / Unggah Bukti' : 'Detail Pesanan' }}
                            </a>

                            @if ($order->isCompleted())
                                @foreach ($order->items as $item)
                                    @if (isset($downloadLinks[$item->id]))
                                        <a href="{{ $downloadLinks[$item->id] }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#22C55E] px-4 text-sm font-bold text-[#0B0D17] hover:bg-[#16A34A]">
                                            Unduh Mod
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
