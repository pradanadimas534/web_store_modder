<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' | ModGame.id' : 'ModGame.id | Marketplace Mod Game' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#0B0D17] text-[#F8FAFC] antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-xl focus:bg-[#7C4DFF] focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Lewati ke konten utama
    </a>

    <header class="sticky top-0 z-40 border-b border-[#30364D] bg-[#121525]">
        <div class="mx-auto flex max-w-[1320px] items-center justify-between gap-4 px-4 py-3 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="inline-flex min-h-[44px] items-center gap-2 text-lg font-extrabold tracking-tight text-[#F8FAFC]">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#7C4DFF] text-sm font-extrabold text-white">MG</span>
                    <span>ModGame<span class="text-[#A78BFA]">.id</span></span>
                </a>

                <nav aria-label="Navigasi utama" class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('home') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold transition-colors {{ request()->routeIs('home') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#AAB2C5] hover:bg-[#1A1E30] hover:text-[#F8FAFC]' }}">
                        Beranda
                    </a>
                    <a href="{{ route('catalog') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold transition-colors {{ request()->routeIs('catalog', 'products.show') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#AAB2C5] hover:bg-[#1A1E30] hover:text-[#F8FAFC]' }}">
                        Katalog Mod
                    </a>
                    <a href="{{ route('ads.submit') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold transition-colors {{ request()->routeIs('ads.submit') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#AAB2C5] hover:bg-[#1A1E30] hover:text-[#F8FAFC]' }}">
                        Pasang Iklan
                    </a>
                    @auth
                        <a href="{{ route('wishlist') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold transition-colors {{ request()->routeIs('wishlist') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#AAB2C5] hover:bg-[#1A1E30] hover:text-[#F8FAFC]' }}">
                            Wishlist
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="hidden items-center gap-2 md:flex">
                @auth
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-4 text-sm font-semibold text-[#F8FAFC] transition-colors hover:border-[#7C4DFF]">
                        {{ auth()->user()->role->label() }}: {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold text-[#AAB2C5] transition-colors hover:bg-[#1A1E30] hover:text-[#F87171]">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] px-4 text-sm font-semibold text-[#F8FAFC] transition-colors hover:bg-[#1A1E30]">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex min-h-[44px] items-center rounded-xl bg-[#7C4DFF] px-4 text-sm font-semibold text-white transition-colors hover:bg-[#6D3DF5]">
                        Daftar
                    </a>
                @endauth
            </div>

            <button
                type="button"
                id="mobile-menu-toggle"
                aria-expanded="false"
                aria-controls="mobile-menu-drawer"
                class="inline-flex min-h-[44px] items-center gap-2 rounded-xl border border-[#30364D] bg-[#1A1E30] px-3.5 text-sm font-semibold text-[#F8FAFC] md:hidden"
            >
                <span>Menu</span>
            </button>
        </div>

        <div id="mobile-menu-drawer" class="hidden border-t border-[#30364D] bg-[#121525] px-4 py-4 md:hidden">
            <nav aria-label="Navigasi mobile" class="flex flex-col gap-2">
                <a href="{{ route('home') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold {{ request()->routeIs('home') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#F8FAFC]' }}">
                    Beranda
                </a>
                <a href="{{ route('catalog') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold {{ request()->routeIs('catalog', 'products.show') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#F8FAFC]' }}">
                    Katalog Mod
                </a>
                <a href="{{ route('ads.submit') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold {{ request()->routeIs('ads.submit') ? 'bg-[#1A1E30] text-[#A78BFA]' : 'text-[#F8FAFC]' }}">
                    Pasang Iklan
                </a>
                @auth
                    <a href="{{ route('wishlist') }}" class="inline-flex min-h-[44px] items-center rounded-xl px-3 text-sm font-semibold text-[#F8FAFC]">
                        Wishlist
                    </a>
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="inline-flex min-h-[44px] items-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-3 text-sm font-semibold text-[#A78BFA]">
                        Dashboard {{ auth()->user()->role->label() }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-[#F87171]/40 px-3 text-sm font-semibold text-[#F87171]">
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-[#30364D] px-3 text-sm font-semibold text-[#F8FAFC]">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-[#7C4DFF] px-3 text-sm font-semibold text-white">
                            Daftar
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="mx-auto mt-4 max-w-[1320px] px-4 lg:px-8">
            <div role="status" class="rounded-xl border border-[#22C55E]/40 bg-[#22C55E]/15 px-4 py-3 text-sm font-medium text-[#22C55E]">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-auto mt-4 max-w-[1320px] px-4 lg:px-8">
            <div role="alert" class="rounded-xl border border-[#F87171]/40 bg-[#F87171]/15 px-4 py-3 text-sm font-medium text-[#F87171]">
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

    <footer class="border-t border-[#30364D] bg-[#121525]">
        <div class="mx-auto flex max-w-[1320px] flex-col justify-between gap-6 px-4 py-8 sm:flex-row sm:items-center lg:px-8">
            <div>
                <p class="text-base font-extrabold text-[#F8FAFC]">ModGame<span class="text-[#A78BFA]">.id</span></p>
                <p class="mt-1 text-sm text-[#AAB2C5]">Marketplace mod game gratis dan berbayar untuk komunitas simulator.</p>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-[#AAB2C5]">
                <a href="{{ route('catalog') }}" class="hover:text-[#F8FAFC]">Katalog</a>
                <a href="{{ route('ads.submit') }}" class="hover:text-[#F8FAFC]">Pasang Iklan</a>
                @auth
                    <a href="{{ auth()->user()->dashboardRoute() }}" class="hover:text-[#F8FAFC]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-[#F8FAFC]">Masuk</a>
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
