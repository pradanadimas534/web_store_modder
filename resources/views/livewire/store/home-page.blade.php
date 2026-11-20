<div class="mx-auto max-w-[1320px] px-4 py-8 sm:py-12 lg:px-8">
    <section class="grid items-center gap-8 rounded-2xl border border-[#30364D] bg-[#121525] p-6 sm:p-10 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-[#F8FAFC] sm:text-4xl lg:text-5xl">
                Store Mod Game untuk Komunitas Simulator
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[#AAB2C5]">
                Temukan mod gratis yang bisa langsung diunduh serta mod berbayar resmi langsung dari modder untuk ETS2, ATS, BUSSID, dan game simulasi lainnya.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('catalog') }}" class="inline-flex min-h-[46px] items-center justify-center rounded-xl bg-[#7C4DFF] px-6 text-sm font-bold text-white shadow-[0_0_24px_rgba(124,77,255,0.28)] transition-colors hover:bg-[#6D3DF5]">
                    Jelajahi Katalog Mod
                </a>
                <a href="{{ route('catalog', ['pricing' => 'free']) }}" class="inline-flex min-h-[46px] items-center justify-center rounded-xl border border-[#30364D] bg-[#1A1E30] px-6 text-sm font-semibold text-[#F8FAFC] transition-colors hover:border-[#7C4DFF]">
                    Lihat Mod Gratis
                </a>
            </div>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:col-span-5">
            <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                <p class="text-sm font-bold text-[#F8FAFC]">Unduh Langsung Mod Gratis</p>
                <p class="mt-1 text-xs leading-relaxed text-[#AAB2C5]">Mod gratis dapat diunduh langsung dari halaman detail produk tanpa proses transaksi.</p>
            </div>
            <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                <p class="text-sm font-bold text-[#F8FAFC]">Pembayaran ke Modder</p>
                <p class="mt-1 text-xs leading-relaxed text-[#AAB2C5]">Setiap modder mengatur rekening atau e-wallet sendiri untuk verifikasi pesanan mod berbayar.</p>
            </div>
            <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                <p class="text-sm font-bold text-[#F8FAFC]">File Privat Terproteksi</p>
                <p class="mt-1 text-xs leading-relaxed text-[#AAB2C5]">Arsip mod berbayar disimpan di storage privat dan dibuka lewat tautan sementara setelah verifikasi.</p>
            </div>
            <div class="rounded-xl border border-[#30364D] bg-[#1A1E30] p-4">
                <p class="text-sm font-bold text-[#F8FAFC]">Moderasi Admin</p>
                <p class="mt-1 text-xs leading-relaxed text-[#AAB2C5]">Setiap mod dan iklan ditinjau admin agar kompatibilitas dan informasi file tetap jelas.</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="quick-categories-heading" class="mt-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 id="quick-categories-heading" class="text-xl font-bold text-[#F8FAFC]">Pilih Berdasarkan Game & Kategori</h2>
                <p class="mt-1 text-sm text-[#AAB2C5]">Saring katalog lebih cepat sesuai game simulasi yang Anda mainkan.</p>
            </div>
            <a href="{{ route('catalog') }}" class="text-sm font-semibold text-[#A78BFA] hover:underline">Lihat semua</a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
            @foreach ($games as $game)
                <a
                    href="{{ route('catalog', ['game' => $game->slug]) }}"
                    class="flex min-h-[76px] flex-col justify-between rounded-xl border border-[#30364D] bg-[#1A1E30] p-4 transition-colors hover:border-[#7C4DFF] hover:bg-[#242943]"
                >
                    <span class="text-xs font-bold text-[#38BDF8]">{{ $game->short_code }}</span>
                    <span class="mt-2 text-sm font-semibold text-[#F8FAFC]">{{ $game->name }}</span>
                </a>
            @endforeach

            @foreach ($categories->take(2) as $category)
                <a
                    href="{{ route('catalog', ['category' => $category->slug]) }}"
                    class="flex min-h-[76px] flex-col justify-between rounded-xl border border-[#30364D] bg-[#121525] p-4 transition-colors hover:border-[#7C4DFF] hover:bg-[#1A1E30]"
                >
                    <span class="text-xs font-semibold text-[#A78BFA]">Kategori</span>
                    <span class="mt-2 text-sm font-semibold text-[#F8FAFC]">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <div class="mt-10">
        <x-ad-slot placement="catalog_banner" />
    </div>

    <section aria-labelledby="featured-mods-heading" class="mt-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 id="featured-mods-heading" class="text-xl font-bold text-[#F8FAFC]">Mod Pilihan</h2>
                <p class="mt-1 text-sm text-[#AAB2C5]">Mod aktif yang paling banyak diunduh dan direkomendasikan di ModGame.id.</p>
            </div>
            <a href="{{ route('catalog', ['sort' => 'popular']) }}" class="text-sm font-semibold text-[#A78BFA] hover:underline">Buka katalog</a>
        </div>

        @if ($featuredProducts->isEmpty())
            <div class="mt-5 rounded-2xl border border-[#30364D] bg-[#121525] p-8 text-center">
                <p class="text-base font-semibold text-[#F8FAFC]">Belum ada mod aktif di katalog.</p>
                <p class="mt-1 text-sm text-[#AAB2C5]">Mod yang telah disetujui admin akan tampil di halaman ini.</p>
            </div>
        @else
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </section>

    <section aria-labelledby="free-mods-heading" class="mt-12 grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 id="free-mods-heading" class="text-xl font-bold text-[#F8FAFC]">Mod Gratis Terbaru</h2>
                    <p class="mt-1 text-sm text-[#AAB2C5]">Langsung unduh tanpa perlu proses checkout.</p>
                </div>
                <a href="{{ route('catalog', ['pricing' => 'free']) }}" class="text-sm font-semibold text-[#A78BFA] hover:underline">Semua mod gratis</a>
            </div>

            @if ($freeProducts->isEmpty())
                <div class="mt-5 rounded-2xl border border-[#30364D] bg-[#121525] p-8 text-center">
                    <p class="text-base font-semibold text-[#F8FAFC]">Belum ada mod gratis tersedia.</p>
                    <p class="mt-1 text-sm text-[#AAB2C5]">Cek katalog lengkap untuk melihat seluruh mod yang aktif.</p>
                </div>
            @else
                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    @foreach ($freeProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            @endif
        </div>

        <div class="space-y-4 lg:col-span-4">
            <div class="rounded-2xl border border-[#30364D] bg-[#121525] p-6">
                <h2 class="text-lg font-bold text-[#F8FAFC]">Ingin Mempromosikan Karya atau Server?</h2>
                <p class="mt-2 text-sm leading-relaxed text-[#AAB2C5]">
                    ModGame.id menyediakan slot banner promosi non-intrusif di katalog, sidebar, dan footer agar iklan tetap terlihat tanpa mengganggu pengalaman pengguna.
                </p>
                <a href="{{ route('ads.submit') }}" class="mt-4 inline-flex min-h-[44px] w-full items-center justify-center rounded-xl border border-[#7C4DFF] bg-[#1A1E30] px-4 text-sm font-semibold text-[#F8FAFC] transition-colors hover:bg-[#7C4DFF]">
                    Ajukan Pemasangan Iklan
                </a>
            </div>

            <x-ad-slot placement="sidebar_promo" />
        </div>
    </section>
</div>
