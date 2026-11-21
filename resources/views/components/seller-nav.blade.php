<nav aria-label="Menu Modder" class="mb-6 flex flex-wrap gap-2 border-b border-[#29342D] pb-4">
    <a href="{{ route('seller.dashboard') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.dashboard') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Ringkasan
    </a>
    <a href="{{ route('seller.products') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.products') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Mod Saya
    </a>
    <a href="{{ route('seller.payments') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.payments') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Metode Pembayaran
    </a>
    <a href="{{ route('seller.orders') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.orders') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Pesanan Masuk
    </a>
    <a href="{{ route('seller.settlements') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.settlements') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Setoran Admin
    </a>
    <a href="{{ route('seller.profile') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('seller.profile') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Profil Toko
    </a>
</nav>
