<?php

namespace Database\Seeders;

use App\Enums\AdPaymentStatus;
use App\Enums\AdPlacement;
use App\Enums\AdStatus;
use App\Enums\PricingType;
use App\Enums\ProductStatus;
use App\Enums\UserRole;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Game;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\SellerPaymentMethod;
use App\Models\SellerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        PlatformSetting::current();

        $admin = User::updateOrCreate(
            ['email' => 'admin@modgame.id'],
            [
                'name' => 'Admin ModGame.id',
                'password' => 'password123',
                'role' => UserRole::Admin,
                'is_active' => true,
            ]
        );

        $seller = User::updateOrCreate(
            ['email' => 'modder@modgame.id'],
            [
                'name' => 'Nusantara Garage',
                'password' => 'password123',
                'role' => UserRole::Seller,
                'is_active' => true,
            ]
        );

        SellerProfile::updateOrCreate(
            ['user_id' => $seller->id],
            [
                'store_name' => 'Nusantara Garage Mod',
                'slug' => 'nusantara-garage-mod',
                'bio' => 'Studio modifikasi kendaraan dan peta jalan Indonesia untuk game simulasi.',
                'whatsapp' => '081234567890',
                'is_verified' => true,
            ]
        );

        SellerPaymentMethod::updateOrCreate(
            ['user_id' => $seller->id, 'provider_name' => 'BCA'],
            [
                'account_number' => '1234567890',
                'account_holder' => 'Nusantara Garage',
                'instructions' => 'Transfer sesuai nominal tagihan lalu unggah bukti transfer di halaman pesanan.',
                'is_active' => true,
            ]
        );

        SellerPaymentMethod::updateOrCreate(
            ['user_id' => $seller->id, 'provider_name' => 'DANA'],
            [
                'account_number' => '081234567890',
                'account_holder' => 'Nusantara Garage',
                'instructions' => 'Kirim via DANA dan sertakan nomor order di catatan transfer.',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'buyer@modgame.id'],
            [
                'name' => 'Pembeli Demo',
                'password' => 'password123',
                'role' => UserRole::User,
                'is_active' => true,
            ]
        );

        $games = [
            ['name' => 'GTA Series', 'slug' => 'gta-series', 'short_code' => 'GTA V', 'cover_path' => 'assets/game-covers/open-world.svg', 'description' => 'Mod kendaraan, script, grafik, dan map untuk seri Grand Theft Auto.', 'sort_order' => 1],
            ['name' => 'Minecraft', 'slug' => 'minecraft', 'short_code' => 'Minecraft', 'cover_path' => 'assets/game-covers/sandbox.svg', 'description' => 'Shader, resource pack, world, dan plugin untuk Minecraft Java & Bedrock.', 'sort_order' => 2],
            ['name' => 'Genshin Impact', 'slug' => 'genshin-impact', 'short_code' => 'Genshin', 'cover_path' => 'assets/game-covers/fantasy-rpg.svg', 'description' => 'Skin karakter, preset visual, dan overlay UI untuk Genshin Impact.', 'sort_order' => 3],
            ['name' => 'Euro Truck Simulator 2', 'slug' => 'ets2', 'short_code' => 'ETS2', 'cover_path' => 'assets/game-covers/simulation.svg', 'description' => 'Mod truk, bus, peta Indonesia, dan aksesori untuk ETS2.', 'sort_order' => 4],
            ['name' => 'American Truck Simulator', 'slug' => 'ats', 'short_code' => 'ATS', 'cover_path' => 'assets/game-covers/simulation.svg', 'description' => 'Koleksi truk Amerika, trailer, dan skin untuk ATS.', 'sort_order' => 5],
            ['name' => 'Red Dead Redemption 2', 'slug' => 'rdr2', 'short_code' => 'RDR2', 'cover_path' => 'assets/game-covers/open-world.svg', 'description' => 'Overhaul visual, kostum, dan script gameplay untuk RDR2.', 'sort_order' => 6],
            ['name' => 'Need for Speed', 'slug' => 'nfs', 'short_code' => 'NFS', 'cover_path' => 'assets/game-covers/racing.svg', 'description' => 'Car pack, livery, dan handling tuning untuk seri Need for Speed.', 'sort_order' => 7],
            ['name' => 'The Sims & Survival', 'slug' => 'the-sims', 'short_code' => 'The Sims', 'cover_path' => 'assets/game-covers/survival.svg', 'description' => 'Custom content, furniture pack, dan mod gameplay.', 'sort_order' => 8],
            ['name' => 'Bus Simulator Indonesia', 'slug' => 'bussid', 'short_code' => 'BUSSID', 'cover_path' => 'assets/game-covers/simulation.svg', 'description' => 'Mod bus HD/SHD, livery, dan map untuk BUSSID.', 'sort_order' => 9],
        ];

        foreach ($games as $gameData) {
            Game::updateOrCreate(['slug' => $gameData['slug']], $gameData);
        }

        $categories = [
            ['name' => 'Kendaraan & Truck', 'slug' => 'truck', 'description' => 'Mod mobil, truk, dan kendaraan lengkap dengan interior.', 'sort_order' => 1],
            ['name' => 'Shader & Grafik', 'slug' => 'shader', 'description' => 'Preset visual, lighting, dan peningkatan tekstur.', 'sort_order' => 2],
            ['name' => 'Map & World', 'slug' => 'map', 'description' => 'Peta rute jalan tambahan, world, dan save game.', 'sort_order' => 3],
            ['name' => 'Skin & Karakter', 'slug' => 'skin', 'description' => 'Livery, pakaian karakter, dan cat kustom.', 'sort_order' => 4],
            ['name' => 'Script & Add-on', 'slug' => 'aksesori', 'description' => 'Plugin, script mekanik, dan aksesori tambahan.', 'sort_order' => 5],
            ['name' => 'Bundle Hemat', 'slug' => 'bundle', 'description' => 'Paket hemat gabungan beberapa mod pilihan.', 'sort_order' => 6],
        ];

        foreach ($categories as $catData) {
            Category::updateOrCreate(['slug' => $catData['slug']], $catData);
        }

        $ets2 = Game::where('slug', 'ets2')->first();
        $gta = Game::where('slug', 'gta-series')->first();
        $minecraft = Game::where('slug', 'minecraft')->first();
        $genshin = Game::where('slug', 'genshin-impact')->first();
        $bussid = Game::where('slug', 'bussid')->first();
        $truckCat = Category::where('slug', 'truck')->first();
        $shaderCat = Category::where('slug', 'shader')->first();
        $mapCat = Category::where('slug', 'map')->first();
        $skinCat = Category::where('slug', 'skin')->first();
        $bundleCat = Category::where('slug', 'bundle')->first();

        $freeFilePath = 'mods/sample-skin-nusantara.zip';
        $paidFilePath = 'mods/sample-map-pantura-pro.zip';
        Storage::disk('local')->put($freeFilePath, 'ModGame.id Sample Free Mod Archive');
        Storage::disk('local')->put($paidFilePath, 'ModGame.id Sample Paid Mod Archive');

        Product::updateOrCreate(
            ['slug' => 'realistic-car-pack-gta-v'],
            [
                'seller_id' => $seller->id,
                'game_id' => $gta->id,
                'category_id' => $truckCat->id,
                'name' => 'Realistic Street Car Pack v3.5',
                'short_description' => 'Paket 8 mobil sport dan JDM dengan handling kustom serta interior beresolusi tinggi untuk GTA V.',
                'description' => 'Paket kendaraan GTA V dengan detail mesin, dashboard aktif, serta konfigurasi handling yang seimbang untuk mode single-player maupun FiveM.',
                'pricing_type' => PricingType::Paid,
                'price' => 49000,
                'promo_price' => null,
                'mod_version' => '3.5.0',
                'compatible_game_version' => 'GTA V Build 3095+',
                'dependencies' => 'Script Hook V & OpenIV',
                'install_guide' => "1. Buka OpenIV dan aktifkan Edit Mode.\n2. Salin folder dlcpack ke mods/update/x64/dlcpacks.\n3. Tambahkan baris item pada dlclist.xml.",
                'changelog' => 'v3.5.0: Penambahan 2 varian velg dan perbaikan refleksi kaca.',
                'file_path' => $paidFilePath,
                'file_original_name' => 'realistic-street-car-pack-v3.5.zip',
                'file_size' => Storage::disk('local')->size($paidFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($paidFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'realistic-shader-pack-minecraft'],
            [
                'seller_id' => $seller->id,
                'game_id' => $minecraft->id,
                'category_id' => $shaderCat->id,
                'name' => 'Emerald Atmospheric Shader v2.8',
                'short_description' => 'Shader pencahayaan sinematik, air jernih, dan bayangan dinamis yang ringan untuk Minecraft.',
                'description' => 'Dirancang untuk menghadirkan pencahayaan pagi dan malam yang tajam serta pantulan air realistis tanpa mengorbankan performa di GPU kelas menengah.',
                'pricing_type' => PricingType::Free,
                'price' => 0,
                'promo_price' => null,
                'mod_version' => '2.8.0',
                'compatible_game_version' => 'Minecraft 1.20 - 1.21 (Iris / OptiFine)',
                'dependencies' => 'Iris Shaders atau OptiFine',
                'install_guide' => 'Salin file .zip ke folder shaderpacks lalu aktifkan melalui menu Video Settings > Shaders.',
                'changelog' => 'v2.8.0: Optimasi kabut volumetrik dan dukungan blok versi 1.21.',
                'file_path' => $freeFilePath,
                'file_original_name' => 'emerald-atmospheric-shader-v2.8.zip',
                'file_size' => Storage::disk('local')->size($freeFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($freeFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'character-skin-overhaul-genshin'],
            [
                'seller_id' => $seller->id,
                'game_id' => $genshin->id,
                'category_id' => $skinCat->id,
                'name' => 'Obsidian Valkyrie Costume Pack',
                'short_description' => 'Kostum alternatif bertema obsidian emerald dengan efek partikel kustom.',
                'description' => 'Paket skin karakter dengan tekstur tajam dan variasi warna aksen hijau emerald yang kompatibel dengan 3DMigoto.',
                'pricing_type' => PricingType::Paid,
                'price' => 35000,
                'promo_price' => 25000,
                'mod_version' => '1.2.0',
                'compatible_game_version' => 'Genshin Impact 5.x',
                'dependencies' => '3DMigoto GIMI Loader',
                'install_guide' => 'Ekstrak folder ke direktori Mods pada GIMI lalu tekan F10 di dalam game untuk memuat ulang.',
                'changelog' => 'v1.2.0: Perbaikan rigging jubah saat berlari.',
                'file_path' => $paidFilePath,
                'file_original_name' => 'obsidian-valkyrie-costume-v1.2.zip',
                'file_size' => Storage::disk('local')->size($paidFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($paidFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'bundle-simulator-nusantara-pack'],
            [
                'seller_id' => $seller->id,
                'game_id' => $ets2->id,
                'category_id' => $bundleCat->id,
                'name' => 'Bundle Nusantara Map + Livery Pack',
                'short_description' => 'Paket hemat berisi Map Jalur Pantura, 10 livery ekspedisi, dan trafik lokal Indonesia.',
                'description' => 'Solusi lengkap untuk langsung menjelajahi rute Indonesia di ETS2 dengan paket map, trafik, dan skin yang sudah teruji saling kompatibel.',
                'pricing_type' => PricingType::Paid,
                'price' => 95000,
                'promo_price' => 69000,
                'mod_version' => '4.0.0',
                'compatible_game_version' => 'ETS2 1.50 - 1.53',
                'dependencies' => 'DLC Going East & Scandinavia',
                'install_guide' => 'Ekstrak ketiga file .scs ke folder mod dan urutkan sesuai panduan di dalam arsip.',
                'changelog' => 'v4.0.0: Pembaruan kompatibilitas penuh untuk ETS2 1.53.',
                'file_path' => $paidFilePath,
                'file_original_name' => 'bundle-nusantara-v4.0.zip',
                'file_size' => Storage::disk('local')->size($paidFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($paidFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'skin-pack-logistik-nusantara-ets2'],
            [
                'seller_id' => $seller->id,
                'game_id' => $ets2->id,
                'category_id' => $skinCat->id,
                'name' => 'Skin Pack Logistik Nusantara',
                'short_description' => 'Paket 6 livery truk logistik Indonesia beresolusi 4K untuk ETS2.',
                'description' => "Paket skin ini berisi 6 desain livery armada logistik Indonesia untuk Scania R/S dan Volvo FH.\n\nCocok untuk dimainkan di rute standar maupun map Indonesia tanpa menurunkan FPS.",
                'pricing_type' => PricingType::Free,
                'price' => 0,
                'promo_price' => null,
                'mod_version' => '1.4.0',
                'compatible_game_version' => 'ETS2 1.50 - 1.53',
                'dependencies' => 'Tidak memerlukan DLC tambahan',
                'install_guide' => "1. Unduh file arsip.\n2. Pindahkan file ke folder Documents/Euro Truck Simulator 2/mod.\n3. Aktifkan di Mod Manager dengan prioritas di atas truk standar.",
                'changelog' => "v1.4.0: Penyesuaian tekstur untuk versi game 1.53.\nv1.3.0: Penambahan varian trailer box.",
                'file_path' => $freeFilePath,
                'file_original_name' => 'skin-pack-logistik-nusantara-v1.4.zip',
                'file_size' => Storage::disk('local')->size($freeFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($freeFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'map-jalur-pantura-pro-ets2'],
            [
                'seller_id' => $seller->id,
                'game_id' => $ets2->id,
                'category_id' => $mapCat->id,
                'name' => 'Map Jalur Pantura Pro',
                'short_description' => 'Peta jalur lintas utara Jawa lengkap dengan gerbang tol, terminal, dan cuaca tropis.',
                'description' => "Map Jalur Pantura Pro menghadirkan 18 kota utama dengan detail persimpangan, lampu jalan, dan lalu lintas lokal.\n\nTermasuk pembaruan gratis selama satu siklus versi mayor ETS2.",
                'pricing_type' => PricingType::Paid,
                'price' => 75000,
                'promo_price' => 59000,
                'mod_version' => '2.2.1',
                'compatible_game_version' => 'ETS2 1.51 - 1.53',
                'dependencies' => 'DLC Going East & Scandinavia',
                'install_guide' => "1. Ekstrak arsip setelah pembayaran terverifikasi.\n2. Letakkan file def, map, dan assets ke folder mod.\n3. Urutkan dari atas ke bawah: Def -> Map -> Assets.",
                'changelog' => "v2.2.1: Perbaikan kolisi jembatan di ruas Cirebon.\nv2.2.0: Penambahan rest area KM 166.",
                'file_path' => $paidFilePath,
                'file_original_name' => 'map-jalur-pantura-pro-v2.2.1.zip',
                'file_size' => Storage::disk('local')->size($paidFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($paidFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'truk-hino-500-bak-kayu-bussid'],
            [
                'seller_id' => $seller->id,
                'game_id' => $bussid->id,
                'category_id' => $truckCat->id,
                'name' => 'Hino 500 Bak Kayu Muatan Sawit',
                'short_description' => 'Mod truk Hino 500 dengan suspensi realistis dan animasi pintu kabin.',
                'description' => 'Mod truk Hino 500 khusus BUSSID dengan detail kabin penuh, speedometer aktif, serta dua pilihan muatan.',
                'pricing_type' => PricingType::Paid,
                'price' => 35000,
                'promo_price' => null,
                'mod_version' => '1.1.0',
                'compatible_game_version' => 'BUSSID 4.2+',
                'dependencies' => 'Tidak ada',
                'install_guide' => 'Salin file ke folder BUSSID/Mods lalu pilih kendaraan di garasi.',
                'changelog' => 'v1.1.0: Perbaikan animasi wiper dan lampu sein.',
                'file_path' => $paidFilePath,
                'file_original_name' => 'hino-500-bak-kayu-v1.1.zip',
                'file_size' => Storage::disk('local')->size($paidFilePath),
                'file_checksum' => hash('sha256', Storage::disk('local')->get($paidFilePath)),
                'status' => ProductStatus::Active,
                'is_featured' => true,
            ]
        );

        Ad::updateOrCreate(
            ['title' => 'Promosikan Server Multiplayer atau Channel Modding Anda'],
            [
                'advertiser_id' => $admin->id,
                'advertiser_name' => 'ModGame.id Partner',
                'advertiser_email' => 'ads@modgame.id',
                'description' => 'Pasang banner promosi di posisi strategis yang tetap nyaman dilihat tanpa mengganggu katalog mod.',
                'cta_text' => 'Pasang Iklan',
                'target_url' => 'http://localhost:8000/iklan',
                'placement' => AdPlacement::CatalogBanner,
                'slot_price' => 50000,
                'admin_fee' => 5000,
                'total_price' => 55000,
                'payment_status' => AdPaymentStatus::Paid,
                'verified_at' => now(),
                'verified_by' => $admin->id,
                'status' => AdStatus::Active,
            ]
        );

        Ad::updateOrCreate(
            ['title' => 'Ruang Sponsor Sidebar ModGame.id'],
            [
                'advertiser_id' => $admin->id,
                'advertiser_name' => 'ModGame.id Partner',
                'advertiser_email' => 'ads@modgame.id',
                'description' => 'Tampil rapi di samping katalog dan detail produk untuk menjangkau komunitas simulator.',
                'cta_text' => 'Ajukan Slot',
                'target_url' => 'http://localhost:8000/iklan',
                'placement' => AdPlacement::SidebarPromo,
                'slot_price' => 35000,
                'admin_fee' => 5000,
                'total_price' => 40000,
                'payment_status' => AdPaymentStatus::Paid,
                'verified_at' => now(),
                'verified_by' => $admin->id,
                'status' => AdStatus::Active,
            ]
        );
    }
}
