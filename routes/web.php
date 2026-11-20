<?php

use App\Http\Controllers\AdProofController;
use App\Http\Controllers\AdRedirectController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OrderProofController;
use App\Http\Controllers\SettlementProofController;
use App\Livewire\Account\CheckoutPage;
use App\Livewire\Account\OrderPaymentPage;
use App\Livewire\Account\OrdersPage as BuyerOrdersPage;
use App\Livewire\Account\WishlistPage;
use App\Livewire\Admin\AdsPage as AdminAdsPage;
use App\Livewire\Admin\DashboardPage as AdminDashboardPage;
use App\Livewire\Admin\FeeSettlementsPage as AdminFeeSettlementsPage;
use App\Livewire\Admin\OrdersPage as AdminOrdersPage;
use App\Livewire\Admin\ProductsPage as AdminProductsPage;
use App\Livewire\Admin\SettingsPage as AdminSettingsPage;
use App\Livewire\Admin\UsersPage as AdminUsersPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Seller\DashboardPage as SellerDashboardPage;
use App\Livewire\Seller\FeeSettlementsPage as SellerFeeSettlementsPage;
use App\Livewire\Seller\OrdersPage as SellerOrdersPage;
use App\Livewire\Seller\PaymentMethodsPage;
use App\Livewire\Seller\ProductsPage as SellerProductsPage;
use App\Livewire\Seller\StoreProfilePage;
use App\Livewire\Store\CatalogPage;
use App\Livewire\Store\HomePage;
use App\Livewire\Store\ProductDetailPage;
use App\Livewire\Store\SubmitAdPage;
use Illuminate\Support\Facades\Route;

// Public storefront
Route::livewire('/', HomePage::class)->name('home');
Route::livewire('/produk', CatalogPage::class)->name('catalog');
Route::livewire('/produk/{slug}', ProductDetailPage::class)->name('products.show');
Route::livewire('/iklan', SubmitAdPage::class)->name('ads.submit');
Route::get('/iklan/{ad}/go', AdRedirectController::class)->name('ads.redirect');

// Protected downloads
Route::get('/download/free/{product:slug}', [DownloadController::class, 'free'])
    ->middleware('throttle:downloads')
    ->name('downloads.free');

Route::get('/download/paid/{orderItem}', [DownloadController::class, 'paid'])
    ->middleware(['auth', 'signed', 'throttle:downloads'])
    ->name('downloads.paid');

// Authentication
Route::middleware('guest')->group(function () {
    Route::livewire('/login', LoginPage::class)->name('login');
    Route::livewire('/register', RegisterPage::class)->name('register');
});

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');

// Authenticated buyer routes
Route::middleware(['auth', 'role:user,seller,admin'])->group(function () {
    Route::livewire('/checkout/{product:slug}', CheckoutPage::class)
        ->middleware('throttle:checkout')
        ->name('checkout');
    Route::livewire('/pembayaran/{orderNumber}', OrderPaymentPage::class)->name('orders.payment');
    Route::get('/orders/{order}/proof', [OrderProofController::class, 'show'])->name('orders.proof');
    Route::livewire('/akun/pesanan', BuyerOrdersPage::class)->name('account.orders');
    Route::livewire('/wishlist', WishlistPage::class)->name('wishlist');
});

// Seller (Modder) routes
Route::prefix('seller')
    ->name('seller.')
    ->middleware(['auth', 'role:seller'])
    ->group(function () {
        Route::livewire('/dashboard', SellerDashboardPage::class)->name('dashboard');
        Route::livewire('/produk', SellerProductsPage::class)->name('products');
        Route::livewire('/pembayaran', PaymentMethodsPage::class)->name('payments');
        Route::livewire('/pesanan', SellerOrdersPage::class)->name('orders');
        Route::livewire('/setoran', SellerFeeSettlementsPage::class)->name('settlements');
        Route::livewire('/profil', StoreProfilePage::class)->name('profile');
    });

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::livewire('/dashboard', AdminDashboardPage::class)->name('dashboard');
        Route::livewire('/pengguna', AdminUsersPage::class)->name('users');
        Route::livewire('/produk', AdminProductsPage::class)->name('products');
        Route::livewire('/transaksi', AdminOrdersPage::class)->name('orders');
        Route::livewire('/setoran', AdminFeeSettlementsPage::class)->name('settlements');
        Route::livewire('/iklan', AdminAdsPage::class)->name('ads');
        Route::livewire('/pengaturan', AdminSettingsPage::class)->name('settings');
    });

Route::get('/ads/{ad}/proof', [AdProofController::class, 'show'])
    ->middleware('auth')
    ->name('ads.proof');

Route::get('/settlements/{settlement}/proof', [SettlementProofController::class, 'show'])
    ->middleware('auth')
    ->name('settlements.proof');
