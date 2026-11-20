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
            ['name' => 'Euro Truck Simulator 2', 'slug' => 'ets2', 'short_code' => 'ETS2', 'description' => 'Mod truk, bus, peta Indonesia, dan aksesori untuk ETS2.', 'sort_order' => 1],
            ['name' => 'American Truck Simulator', 'slug' => 'ats', 'short_code' => 'ATS', 'description' => 'Koleksi truk Amerika, trailer, dan skin untuk ATS.', 'sort_order' => 2],
            ['name' => 'Bus Simulator Indonesia', 'slug' => 'bussid', 'short_code' => 'BUSSID', 'description' => 'Mod bus HD/SHD, livery, dan map untuk BUSSID.', 'sort_order' => 3],
            ['name' => 'Game Simulasi Lainnya', 'slug' => 'lainnya', 'short_code' => 'SIM', 'description' => 'Add-on dan mod untuk berbagai game simulasi lainnya.', 'sort_order' => 4],
        ];

        foreach ($games as $gameData) {
            Game::updateOrCreate(['slug' => $gameData['slug']], $gameData);
        }

        $categories = [
            ['name' => 'Truck', 'slug' => 'truck', 'description' => 'Mod kendaraan truk lengkap dengan interior.', 'sort_order' => 1],
            ['name' => 'Bus', 'slug' => 'bus', 'description' => 'Mod bus pariwisata dan AKAP.', 'sort_order' => 2],
            ['name' => 'Map', 'slug' => 'map', 'description' => 'Peta rute jalan tambahan dan wilayah Nusantara.', 'sort_order' => 3],
            ['name' => 'Skin', 'slug' => 'skin', 'description' => 'Livery dan cat kustom beresolusi tinggi.', 'sort_order' => 4],
            ['name' => 'Aksesori', 'slug' => 'aksesori', 'description' => 'Lampu, velg, suara klakson, dan kabin.', 'sort_order' => 5],
            ['name' => 'Bundle', 'slug' => 'bundle', 'description' => 'Paket hemat gabungan beberapa mod.', 'sort_order' => 6],
        ];

        foreach ($categories as $catData) {
            Category::updateOrCreate(['slug' => $catData['slug']], $catData);
        }

        $ets2 = Game::where('slug', 'ets2')->first();
        $bussid = Game::where('slug', 'bussid')->first();
        $truckCat = Category::where('slug', 'truck')->first();
        $mapCat = Category::where('slug', 'map')->first();
        $skinCat = Category::where('slug', 'skin')->first();

        $freeFilePath = 'mods/sample-skin-nusantara.zip';
        $paidFilePath = 'mods/sample-map-pantura-pro.zip';
        Storage::disk('local')->put($freeFilePath, 'ModGame.id Sample Free Mod Archive');
        Storage::disk('local')->put($paidFilePath, 'ModGame.id Sample Paid Mod Archive');

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
