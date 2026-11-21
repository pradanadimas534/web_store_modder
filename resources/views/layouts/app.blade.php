<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' | ModGame.id' : 'ModGame.id | Semua Game, Satu Tempat' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/logo/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#080B0A] text-[#F2F5F0] antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-[10px] focus:bg-[#7CFF00] focus:px-4 focus:py-2 focus:text-sm focus:font-extrabold focus:text-[#080B0A]">
        Lewati ke konten utama
    </a>

    <header class="sticky top-0 z-40 border-b border-[#29342D] bg-[#101614]/95 backdrop-blur-sm">
        <div class="mx-auto flex max-w-[1360px] items-center justify-between gap-4 px-4 py-3 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="inline-flex min-h-[44px] items-center gap-2.5 text-[#F2F5F0]">
                    <img src="{{ asset('assets/logo/favicon.svg') }}" alt="Logo ModGame.id" class="h-9 w-9 rounded-lg">
                    <div class="leading-none">
                        <span class="font-display block text-lg font-extrabold tracking-tight text-[#F2F5F0]">ModGame<span class="text-[#7CFF00]">.id</span></span>
                        <span class="block text-[11px] font-medium text-[#A0AAA2]">Game Mod & Digital Store</span>
                    </div>
                </a>

                <nav aria-label="Navigasi utama" class="hidden items-center gap-1.5 lg:flex">
                    <a href="{{ route('home') }}" class="inline-flex min-h-[38px] items-center rounded-[10px] px-3.5 text-sm font-bold transition-colors {{ request()->routeIs('home') ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#A0AAA2] hover:bg-[#151D19] hover:text-[#F2F5F0]' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog') }}" class="inline-flex min-h-[38px] items-center rounded-[10px] px-3.5 text-sm font-semibold transition-colors {{ request()->routeIs('catalog', 'products.show') && request('category') !== 'bundle' ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#A0AAA2] hover:bg-[#151D19] hover:text-[#F2F5F0]' }}">
                        Katalog
                    </a>
                    <a href="{{ route('catalog', ['category' => 'bundle']) }}" class="inline-flex min-h-[38px] items-center rounded-[10px] px-3.5 text-sm font-semibold transition-colors {{ request('category') === 'bundle' ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#A0AAA2] hover:bg-[#151D19] hover:text-[#F2F5F0]' }}">
                        Bundle
                    </a>
                    <a href="{{ route('catalog', ['sort' => 'latest']) }}" class="inline-flex min-h-[38px] items-center rounded-[10px] px-3.5 text-sm font-semibold text-[#A0AAA2] transition-colors hover:bg-[#151D19] hover:text-[#F2F5F0]">
                        Update
                    </a>
                    <a href="{{ route('ads.submit') }}" class="inline-flex min-h-[38px] items-center rounded-[10px] px-3.5 text-sm font-semibold transition-colors {{ request()->routeIs('ads.submit') ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#A0AAA2] hover:bg-[#151D19] hover:text-[#F2F5F0]' }}">
                        Pasang Iklan
                    </a>
                </nav>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <form action="{{ route('catalog') }}" method="GET" role="search" class="relative w-56 xl:w-72">
                    <label for="header-search" class="sr-only">Cari mod atau game</label>
                    <input
                        id="header-search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari mod, skin, map, addon..."
                        class="min-h-[40px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] pl-3.5 pr-9 text-xs text-[#F2F5F0] placeholder-[#A0AAA2] focus:border-[#7CFF00]"
                    >
                    <button type="submit" aria-label="Cari" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-[#A0AAA2] hover:text-[#7CFF00]">
                        Cari
                    </button>
                </form>

                @auth
                    <a href="{{ route('wishlist') }}" aria-label="Wishlist" title="Wishlist" class="inline-flex h-10 w-10 items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19] transition-colors hover:border-[#7CFF00]">
                        <img src="{{ asset('assets/icons/heart.svg') }}" alt="" class="h-5 w-5">
                    </a>
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-bold text-[#F2F5F0] transition-colors hover:border-[#7CFF00]">
                        {{ auth()->user()->role->label() }}: {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex min-h-[40px] items-center rounded-[10px] px-3 text-xs font-semibold text-[#A0AAA2] transition-colors hover:bg-[#151D19] hover:text-[#FF6262]">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex min-h-[40px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-4 text-xs font-bold text-[#F2F5F0] transition-colors hover:border-[#7CFF00]">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex min-h-[40px] items-center rounded-[10px] bg-[#7CFF00] px-4 text-xs font-extrabold text-[#080B0A] transition-colors hover:bg-[#9BFF3B]">
                        Daftar
                    </a>
                @endauth
            </div>

            <button
                type="button"
                id="mobile-menu-toggle"
                aria-expanded="false"
                aria-controls="mobile-menu-drawer"
                class="inline-flex min-h-[44px] items-center gap-2 rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-xs font-bold text-[#F2F5F0] md:hidden"
            >
                <span>Menu</span>
            </button>
        </div>

        <div id="mobile-menu-drawer" class="hidden border-t border-[#29342D] bg-[#101614] px-4 py-4 md:hidden">
            <form action="{{ route('catalog') }}" method="GET" role="search" class="mb-3">
                <label for="mobile-search" class="sr-only">Cari mod atau game</label>
                <input
                    id="mobile-search"
                    type="search"
                    name="search"
                    placeholder="Cari mod, skin, map, addon..."
                    class="min-h-[44px] w-full rounded-[10px] border border-[#29342D] bg-[#151D19] px-3.5 text-sm text-[#F2F5F0] placeholder-[#A0AAA2]"
                >
            </form>

            <nav aria-label="Navigasi mobile" class="flex flex-col gap-1.5">
                <a href="{{ route('home') }}" class="inline-flex min-h-[44px] items-center rounded-[10px] px-3 text-sm font-bold {{ request()->routeIs('home') ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#F2F5F0]' }}">
                    Beranda
                </a>
                <a href="{{ route('catalog') }}" class="inline-flex min-h-[44px] items-center rounded-[10px] px-3 text-sm font-semibold {{ request()->routeIs('catalog') ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#F2F5F0]' }}">
                    Katalog Mod
                </a>
                <a href="{{ route('catalog', ['category' => 'bundle']) }}" class="inline-flex min-h-[44px] items-center rounded-[10px] px-3 text-sm font-semibold text-[#F2F5F0]">
                    Bundle Hemat
                </a>
                <a href="{{ route('ads.submit') }}" class="inline-flex min-h-[44px] items-center rounded-[10px] px-3 text-sm font-semibold {{ request()->routeIs('ads.submit') ? 'bg-[#7CFF00] text-[#080B0A]' : 'text-[#F2F5F0]' }}">
                    Pasang Iklan
                </a>
                @auth
                    <a href="{{ route('wishlist') }}" class="inline-flex min-h-[44px] items-center rounded-[10px] px-3 text-sm font-semibold text-[#F2F5F0]">
                        Wishlist
                    </a>
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="inline-flex min-h-[44px] items-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 text-sm font-bold text-[#7CFF00]">
                        Dashboard {{ auth()->user()->role->label() }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-[10px] border border-[#FF6262]/40 px-3 text-sm font-semibold text-[#FF6262]">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-[10px] border border-[#29342D] bg-[#151D19] px-3 text-sm font-semibold text-[#F2F5F0]">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-[10px] bg-[#7CFF00] px-3 text-sm font-extrabold text-[#080B0A]">
                            Daftar
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-4 max-w-[1360px] px-4 lg:px-8">
            <div role="status" class="rounded-xl border border-[#35D07F]/40 bg-[#35D07F]/15 px-4 py-3 text-sm font-medium text-[#35D07F]">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-auto mt-4 max-w-[1360px] px-4 lg:px-8">
            <div role="alert" class="rounded-xl border border-[#FF6262]/40 bg-[#FF6262]/15 px-4 py-3 text-sm font-medium text-[#FF6262]">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main id="main-content" class="min-h-[70vh]">
        {{ $slot }}
    </main>

    <div class="mt-16">
        <x-ad-slot placement="footer_strip" />
    </div>

    <footer class="border-t border-[#29342D] bg-[#101614]">
        <div class="mx-auto flex max-w-[1360px] flex-col justify-between gap-6 px-4 py-8 sm:flex-row sm:items-center lg:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo/favicon.svg') }}" alt="ModGame.id" class="h-8 w-8 rounded-lg">
                <div>
                    <p class="font-display text-base font-extrabold text-[#F2F5F0]">ModGame<span class="text-[#7CFF00]">.id</span></p>
                    <p class="text-xs text-[#A0AAA2]">Semua Game, Satu Tempat. Marketplace mod dan konten digital lintas game.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-5 text-sm font-medium text-[#A0AAA2]">
                <a href="{{ route('catalog') }}" class="hover:text-[#7CFF00]">Katalog</a>
                <a href="{{ route('catalog', ['category' => 'bundle']) }}" class="hover:text-[#7CFF00]">Bundle</a>
                <a href="{{ route('ads.submit') }}" class="hover:text-[#7CFF00]">Pasang Iklan</a>
                @auth
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="hover:text-[#7CFF00]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-[#7CFF00]">Masuk</a>
                @endauth
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('mobile-menu-toggle');
            const drawer = document.getElementById('mobile-menu-drawer');
            if (!toggle || !drawer) return;

            toggle.addEventListener('click', function () {
                const isOpen = !drawer.classList.contains('hidden');
                drawer.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', String(!isOpen));
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !drawer.classList.contains('hidden')) {
                    drawer.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.focus();
                }
            });
        });
    </script>
    @livewireScripts
</body>
</html>
