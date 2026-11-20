<nav aria-label="Menu Admin" class="mb-6 flex flex-wrap gap-2 border-b border-[#30364D] pb-4">
    <a href="{{ route('admin.dashboard') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Ringkasan
    </a>
    <a href="{{ route('admin.users') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.users') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Pengguna & Modder
    </a>
    <a href="{{ route('admin.products') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.products') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Moderasi Mod
    </a>
    <a href="{{ route('admin.orders') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.orders') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Transaksi
    </a>
    <a href="{{ route('admin.settlements') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.settlements') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Setoran Modder
    </a>
    <a href="{{ route('admin.ads') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.ads') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Kelola Iklan (AdSense)
    </a>
    <a href="{{ route('admin.settings') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('admin.settings') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Pengaturan Biaya
    </a>
</nav>
