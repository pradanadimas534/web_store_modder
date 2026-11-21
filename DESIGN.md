# ModGame.id — Design System & UI/UX Specification

**Versi:** 2.0  
**Arah visual:** Obsidian / Acid Green Gaming  
**Jenis produk:** Marketplace mod dan konten digital lintas game

## 1. Visi Produk

ModGame.id adalah marketplace untuk menemukan, membeli, dan mengunduh mod maupun konten digital dari berbagai jenis game. Platform tidak dibatasi pada game simulator. Kategori dapat mencakup GTA, Minecraft, Genshin Impact, ETS2/ATS, Red Dead Redemption, FIFA/EA Sports FC, The Sims, Need for Speed, game RPG, survival, sandbox, dan game lain yang ditambahkan admin.

Jenis produk yang dapat dijual:
- Mod, add-on, plugin, script, dan resource pack.
- Map, world, save game, dan preset.
- Skin, karakter, kendaraan, texture, dan cosmetic.
- Tools, konfigurasi, serta konten digital pendukung.
- Bundle beberapa produk.

**Prinsip produk:** discovery lintas game, detail kompatibilitas yang jelas, transaksi tepercaya, dan download yang aman.

## 2. Arah Art Direction

### Nama gaya: Obsidian Acid

Berbeda dari toko game yang ramai dengan gradasi ungu/biru, ModGame.id menggunakan:
- Hitam obsidian sebagai kanvas utama.
- Hijau acid/lime sebagai aksen interaksi.
- Hijau emerald sebagai aksen sekunder.
- Garis grid dan tekstur teknis yang sangat halus.
- Komposisi editorial dengan whitespace yang cukup.
- Tipografi tegas dan sedikit condensed untuk heading.
- Gambar game menjadi warna utama; UI tetap tenang dan gelap.

**Hindari:** glow di semua elemen, gradient berlebihan, terlalu banyak garis neon, font futuristik yang sulit dibaca, serta layout yang menyerupai dashboard admin pada halaman toko.

### 2.1 Color tokens

| Token | Hex | Fungsi |
|---|---|---|
| `--color-bg` | `#080B0A` | Latar halaman |
| `--color-surface` | `#101614` | Panel dan header |
| `--color-surface-2` | `#151D19` | Kartu dan dropdown |
| `--color-surface-3` | `#1C2721` | Hover dan elevated surface |
| `--color-primary` | `#7CFF00` | CTA dan state aktif |
| `--color-primary-hover` | `#9BFF3B` | Hover CTA |
| `--color-secondary` | `#00C853` | Aksen sekunder |
| `--color-lime-muted` | `#B6E879` | Highlight lembut |
| `--color-text` | `#F2F5F0` | Teks utama |
| `--color-muted` | `#A0AAA2` | Teks sekunder |
| `--color-border` | `#29342D` | Border |
| `--color-success` | `#35D07F` | Berhasil |
| `--color-warning` | `#F5C451` | Perhatian |
| `--color-danger` | `#FF6262` | Error |

Rasio kontras teks wajib diperiksa. Hijau neon digunakan terutama untuk elemen besar/tebal, bukan teks kecil dalam jumlah banyak.

### 2.2 Typography

- **UI/body:** Inter.
- **Display/heading:** Space Grotesk atau Rajdhani.
- **Fallback:** system-ui, sans-serif.
- Heading utama: 48–64 px desktop, 34–42 px mobile, weight 700–800.
- Heading section: 24–30 px.
- Body: 14–16 px.
- Metadata: minimum 12 px; informasi penting tidak boleh hanya ditampilkan sebagai metadata kecil.

### 2.3 Shape, spacing, effects

- Radius kartu: 14–18 px.
- Radius input: 10–12 px.
- Radius tombol: 10 px.
- Grid spacing: kelipatan 4/8 px.
- Border 1 px, kontras rendah.
- Shadow ringan dan natural.
- Glow hanya pada CTA utama, focus ring, dan state aktif.
- Motion 160–220 ms; hormati `prefers-reduced-motion`.

## 3. Layout & Grid

- Max content width: 1360 px.
- Desktop gutter: 32–48 px.
- Tablet gutter: 24 px.
- Mobile gutter: 16 px.
- Header desktop: tinggi sekitar 64 px.
- Product card image ratio: 16:10.
- Game cover ratio: 16:9 atau 5:4.
- Homepage desktop: area konten utama + sidebar promo; mobile menjadi satu kolom.
- Jangan memaksakan sidebar pada mobile.

## 4. Navigasi & Sitemap

```text
/
├── /catalog
│   ├── /catalog?game={slug}
│   ├── /category/{slug}
│   └── /product/{slug}
├── /games
│   └── /games/{slug}
├── /bundles
├── /updates
├── /community
├── /guides
├── /wishlist
├── /cart
├── /checkout
├── /orders/{order-number}
├── /account
│   ├── /account/orders
│   ├── /account/downloads
│   ├── /account/reviews
│   └── /account/settings
├── /seller
│   ├── /seller/products
│   ├── /seller/orders
│   └── /seller/earnings
└── /admin
```

Header:
- Logo.
- Beranda.
- Katalog.
- Game dropdown.
- Bundle.
- Update.
- Komunitas.
- Search.
- Wishlist.
- Cart.
- Account.

Mobile: logo, search, cart, hamburger; navigasi lain masuk drawer.

## 5. Homepage

### 5.1 Hero

Headline: **“Semua Game, Satu Tempat.”**

Subheadline: “Temukan dan beli berbagai mod, addon, map, skin, resource pack, script, dan konten eksklusif untuk game favoritmu.”

CTA:
- Primary: Jelajahi Katalog.
- Secondary: Game Populer.

Trust strip:
- Aman & Terverifikasi.
- Download Setelah Pembayaran.
- Support Cepat.

Visual hero menggunakan kolase cover dari beberapa genre/game, bukan hanya simulator. Untuk produksi, gunakan artwork yang memiliki izin penggunaan atau aset original.

### 5.2 Game category rail

Tile kategori dapat di-scroll horizontal:
- Semua Game.
- GTA Series.
- Minecraft.
- Genshin Impact.
- ETS2.
- ATS.
- Red Dead Redemption 2.
- EA Sports FC.
- Need for Speed.
- The Sims.
- Lainnya.

Kategori bukan daftar permanen. Admin dapat menambah game baru dari panel admin.

### 5.3 Product discovery

Section awal:
- Produk Terbaru.
- Terlaris Minggu Ini.
- Update Terbaru.
- Bundle Hemat.
- Pilihan Komunitas.

Product card menampilkan:
- Cover/thumbnail.
- Game badge.
- Judul.
- Jenis konten.
- Rating dan jumlah review (jika ada).
- Harga dan diskon (jika valid).
- Quick add-to-cart atau detail.

### 5.4 Sidebar desktop

Panel yang dapat berisi:
- Kenapa ModGame.id?
- Bundle Game Pack.
- Komunitas ModGame.id.

Pada mobile panel dipindah ke bawah section produk.

## 6. Katalog

Desktop:
- Filter sidebar kiri.
- Grid produk kanan.
- Toolbar: jumlah hasil, sort, tampilan grid/list.

Mobile:
- Tombol Filter membuka bottom sheet.
- Sort tetap mudah dijangkau.
- Filter aktif ditampilkan sebagai chips.

Filter:
- Game.
- Kategori konten.
- Harga.
- Versi game.
- Platform (PC/Android/console jika relevan).
- Rating.
- Status update.
- Kompatibilitas/DLC/dependency.

Sort:
- Relevan.
- Terbaru.
- Terlaris.
- Rating tertinggi.
- Harga terendah/tertinggi.

## 7. Detail Produk

Urutan:
1. Breadcrumb.
2. Gallery image/video.
3. Judul, game, kategori, rating.
4. Harga, diskon, CTA.
5. Ringkasan dan fitur.
6. Kompatibilitas dan requirements.
7. Deskripsi lengkap.
8. Cara instalasi.
9. Changelog dan riwayat versi.
10. Ulasan terverifikasi.
11. Produk terkait.

Informasi teknis wajib sesuai tipe produk:
- Game dan platform.
- Versi game yang didukung.
- Versi mod.
- DLC/dependency.
- Ukuran dan format file.
- Instruksi instalasi.
- Tanggal update.
- Catatan known issues.

Jangan menyatakan kompatibel jika belum dikonfirmasi oleh seller/admin.

## 8. Cart & Checkout

Cart:
- Item, thumbnail, harga snapshot, remove.
- Kode promo.
- Subtotal dan total.
- CTA lanjut checkout.

Checkout:
- Data pembeli dan email.
- Ringkasan order.
- Metode pembayaran.
- Persetujuan ketentuan.
- Total akhir.
- Tombol bayar.

Akses file baru aktif setelah webhook pembayaran diverifikasi di server. Jangan mengandalkan redirect browser sebagai bukti pembayaran.

## 9. Account & Downloads

Pembeli:
- Overview.
- Pesanan.
- Download.
- Wishlist.
- Review.
- Pengaturan akun.
- Bantuan.

Download:
- Product name.
- Versi file.
- Tanggal pembelian.
- Tanggal update.
- Tombol download.
- Catatan instalasi.

Gunakan private storage dan signed URL dengan masa berlaku. Validasi kepemilikan order di backend pada setiap permintaan download.

## 10. Seller & Admin

Seller:
- Dashboard.
- Produk.
- Tambah/edit produk.
- Pesanan.
- Review.
- Pendapatan.
- Profil toko.

Produk baru memiliki status Draft, Pending Review, Published, Rejected, atau Disabled. Seller hanya boleh mengubah data miliknya.

Admin:
- Ringkasan transaksi.
- Produk dan moderasi.
- Game dan kategori.
- Pengguna dan seller.
- Order, payment, refund.
- Komisi dan payout.
- Banner/homepage.
- Laporan dan audit log.

## 11. Komponen UI

Reusable components:
- `SiteHeader`, `MobileDrawer`, `SiteFooter`
- `SearchBox`, `SearchSuggestion`
- `HeroBanner`, `TrustStrip`
- `GameTile`, `GameRail`
- `ProductCard`, `ProductGrid`, `ProductBadge`
- `Price`, `Rating`, `DiscountLabel`
- `FilterSidebar`, `FilterSheet`, `ActiveFilterChip`
- `SortMenu`, `Pagination`
- `ProductGallery`, `CompatibilityTable`
- `CartLine`, `OrderSummary`
- `CheckoutStepper`, `PaymentMethod`
- `StatusBadge`, `Toast`, `Modal`, `Skeleton`
- `EmptyState`, `ErrorState`

Setiap komponen interaktif harus memiliki default, hover, focus-visible, active, disabled, loading, dan error state bila relevan.

## 12. Responsive Rules

| Breakpoint | Lebar | Perilaku |
|---|---:|---|
| Mobile | <640 px | Satu kolom, filter sheet, drawer |
| Tablet | 640–1023 px | Grid 2–3 kolom |
| Desktop | 1024–1439 px | Filter sidebar, grid 3–4 kolom |
| Wide | >=1440 px | Max-width 1360 px |

Uji minimal pada lebar 360, 390, 768, 1024, 1366, dan 1440 px.

## 13. Assets & Resource

Struktur aset yang disediakan dalam ZIP:
- `/assets/logo` — logo utama dan favicon SVG.
- `/assets/backgrounds` — grid, streak, dan panel texture.
- `/assets/icons` — ikon kategori dan aksi.
- `/assets/game-covers` — placeholder cover original per genre/game category.
- `/assets/ui` — token warna CSS.
- `/mockup` — referensi visual mockup.

Aset game/karakter pihak ketiga dalam mockup hanya referensi visual. Untuk penggunaan komersial, gunakan artwork resmi yang memiliki lisensi atau buat ilustrasi original. Logo ModGame.id yang disediakan adalah identitas original.

## 14. Rekomendasi Implementasi

Frontend:
- React + Vite.
- Tailwind CSS.
- React Router.
- TanStack Query.
- Zustand/Context untuk state cart sesuai skala aplikasi.

Backend:
- Laravel + MySQL.
- Laravel Sanctum jika API terpisah.
- Queue untuk email dan pekerjaan background.
- Private object storage untuk file digital.
- Payment gateway Indonesia dengan webhook signature verification.

Arsitektur dapat disesuaikan dengan batasan hosting. Hindari fitur yang bergantung pada Node/Chromium/process manager di server produksi jika hosting tidak mendukungnya.

## 15. Data Model Minimum

| Tabel | Field penting |
|---|---|
| users | name, email, password_hash, role, status |
| seller_profiles | user_id, store_name, verification_status |
| games | name, slug, cover, status |
| categories | name, slug, parent_id |
| products | seller_id, game_id, category_id, title, slug, price, status |
| product_media | product_id, path, media_type, sort_order |
| product_files | product_id, private_path, version, size, checksum |
| compatibility | product_id, platform, game_version, dependency |
| carts / cart_items | user/session, product, quantity |
| orders / order_items | order number, buyer, price snapshot, status |
| payments | order_id, provider, reference, verified_status |
| downloads | user_id, order_item_id, downloaded_at |
| reviews | user_id, product_id, rating, comment, verified |
| wishlists | user_id, product_id |
| coupons | code, discount_type, value, expiry |
| seller_payouts | seller_id, amount, status |

Simpan harga snapshot di order item. Gunakan transaksi database dan unique constraint untuk nomor order/reference pembayaran.

## 16. Security & Quality Checklist

- Validasi dan authorization di backend.
- Seller tidak dapat mengubah produk seller lain.
- Harga checkout dihitung ulang di server.
- Verifikasi signature webhook dan idempotency.
- File disimpan di private storage.
- Validasi ekstensi, MIME, ukuran, dan isi arsip.
- Download memeriksa hak akses order.
- Rate limit login, checkout, dan download.
- Sanitasi input dan proteksi XSS/CSRF sesuai arsitektur.
- Secret key tidak pernah dikirim ke frontend.
- Audit log untuk tindakan admin dan perubahan transaksi.
- Backup database dan file secara berkala.
- Seller wajib memiliki hak distribusi/penjualan aset yang diunggah; sediakan mekanisme laporan dan takedown.

## 17. SEO, Accessibility & Performance

- Unique title, meta description, canonical per produk.
- Sitemap, robots.txt, dan structured data bila datanya valid.
- Alt text untuk gambar informatif.
- Navigasi keyboard dan focus-visible.
- Kontras teks memadai; status tidak hanya dibedakan dengan warna.
- WebP/AVIF, responsive images, lazy load untuk konten di bawah fold.
- Jangan lazy load hero/LCP image.
- Pagination katalog dan cache data publik.
- Jangan mengirim file mod ke halaman katalog.

## 18. Acceptance Criteria

- Pengunjung dapat menjelajah katalog tanpa login.
- Game dan kategori dapat ditambah admin tanpa mengubah kode frontend.
- Search, filter, dan sorting bekerja bersama.
- Detail produk menunjukkan kompatibilitas dan requirement.
- Cart dan checkout menghitung harga ulang di backend.
- Download hanya tersedia untuk order terverifikasi.
- Seller hanya mengelola produk miliknya.
- Admin dapat memoderasi listing dan transaksi.
- Semua halaman utama responsif.
- Loading, empty, error, success state tersedia.
- Tidak ada tombol/tautan utama yang hanya dekoratif.

## 19. Roadmap

**Phase 1 — Store MVP**
Homepage, katalog, detail, auth, cart, checkout, payment webhook, order history, protected download, admin produk/order.

**Phase 2 — Marketplace**
Seller onboarding, product moderation, commission, payout, reviews, wishlist, coupon, bundle.

**Phase 3 — Growth**
Follow game/seller, notifikasi update mod, changelog, rekomendasi, affiliate, komunitas, analytics.

**Prioritas:** stabilkan pembayaran, hak akses, dan download digital sebelum membuka marketplace multi-seller secara luas.
