<div class="mx-auto max-w-[1200px] px-4 py-8 lg:px-8">
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-[#29342D] pb-5">
        <div>
            <h1 class="font-display text-2xl font-extrabold text-[#F2F5F0]">Pesanan & Unduhan Saya</h1>
            <p class="mt-1 text-sm text-[#A0AAA2]">Pantau status verifikasi pembayaran dan unduh mod berbayar yang telah selesai.</p>
        </div>
        <a href="{{ route('catalog') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
            Jelajahi Katalog
        </a>
    </div>

    @if ($orders->isEmpty())
        <div class="mt-8 rounded-2xl border border-[#29342D] bg-[#101614] p-10 text-center">
            <h2 class="font-display text-lg font-bold text-[#F2F5F0]">Belum ada transaksi mod berbayar</h2>
            <p class="mt-2 text-sm text-[#A0AAA2]">Pesanan mod berbayar yang Anda buat akan muncul di sini beserta tautan unduhan setelah terverifikasi.</p>
            <a href="{{ route('catalog') }}" class="mt-5 inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-5 text-xs font-extrabold text-[#080B0A]">
                Cari Mod di Katalog
            </a>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($orders as $order)
                <article class="rounded-2xl border border-[#29342D] bg-[#101614] p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#29342D] pb-4">
                        <div>
                            <p class="font-display text-sm font-bold text-[#F2F5F0]">{{ $order->order_number }}</p>
                            <p class="text-xs text-[#A0AAA2]">
                                {{ $order->created_at->format('d M Y H:i') }} &middot; Modder: {{ $order->seller->sellerProfile?->store_name ?? $order->seller->name }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-display text-sm font-extrabold text-[#7CFF00]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div class="space-y-1">
                            @foreach ($order->items as $item)
                                <p class="text-sm font-semibold text-[#F2F5F0]">{{ $item->product_name_snapshot }}</p>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('orders.payment', $order->order_number) }}" class="inline-flex min-h-[42px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-bold text-[#F2F5F0] hover:border-[#7CFF00]">
                                {{ $order->canUploadProof() ? 'Bayar / Unggah Bukti' : 'Detail Pesanan' }}
                            </a>

                            @if ($order->isCompleted())
                                @foreach ($order->items as $item)
                                    @if (isset($downloadLinks[$item->id]))
                                        <a href="{{ $downloadLinks[$item->id] }}" class="inline-flex min-h-[42px] items-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] hover:bg-[#9BFF3B]">
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
