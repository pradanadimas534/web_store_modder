<nav aria-label="Menu Modder" class="mb-6 flex flex-wrap gap-2 border-b border-[#30364D] pb-4">
    <a href="{{ route('seller.dashboard') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.dashboard') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Ringkasan
    </a>
    <a href="{{ route('seller.products') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.products') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Mod Saya
    </a>
    <a href="{{ route('seller.payments') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.payments') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Metode Pembayaran
    </a>
    <a href="{{ route('seller.orders') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.orders') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Pesanan Masuk
    </a>
    <a href="{{ route('seller.settlements') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.settlements') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Setoran Admin
    </a>
    <a href="{{ route('seller.profile') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-4 text-sm font-semibold {{ request()->routeIs('seller.profile') ? 'bg-[#7C4DFF] text-white' : 'border border-[#30364D] bg-[#121525] text-[#AAB2C5] hover:text-[#F8FAFC]' }}">
        Profil Toko
    </a>
</nav>
