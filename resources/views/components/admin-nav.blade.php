<nav aria-label="Menu Admin" class="mb-6 flex flex-wrap gap-2 border-b border-[#29342D] pb-4">
    <a href="{{ route('admin.dashboard') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Ringkasan
    </a>
    <a href="{{ route('admin.users') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.users') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Pengguna & Modder
    </a>
    <a href="{{ route('admin.catalog-master') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.catalog-master') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Game & Kategori
    </a>
    <a href="{{ route('admin.products') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.products') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Moderasi Mod
    </a>
    <a href="{{ route('admin.orders') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.orders') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Transaksi
    </a>
    <a href="{{ route('admin.settlements') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.settlements') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Setoran Modder
    </a>
    <a href="{{ route('admin.ads') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.ads') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Kelola Iklan (AdSense)
    </a>
    <a href="{{ route('admin.settings') }}" class="inline-flex min-h-[42px] items-center rounded-[10px] px-4 text-xs font-bold {{ request()->routeIs('admin.settings') ? 'bg-[#7CFF00] text-[#080B0A]' : 'border border-[#29342D] bg-[#101614] text-[#A0AAA2] hover:text-[#F2F5F0]' }}">
        Pengaturan Biaya
    </a>
</nav>
