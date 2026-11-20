<?php

namespace App\Livewire\Seller;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard Modder')]
class DashboardPage extends Component
{
    public function render(): View
    {
        $sellerId = Auth::id();

        $activeProductsCount = Product::query()
            ->where('seller_id', $sellerId)
            ->where('status', ProductStatus::Active)
            ->count();

        $pendingProductsCount = Product::query()
            ->where('seller_id', $sellerId)
            ->where('status', ProductStatus::PendingReview)
            ->count();

        $pendingOrdersCount = Order::query()
            ->where('seller_id', $sellerId)
            ->where('status', OrderStatus::PendingVerification)
            ->count();

        $completedRevenue = (int) Order::query()
            ->where('seller_id', $sellerId)
            ->where('status', OrderStatus::Completed)
            ->sum('seller_net_amount');

        $activePaymentMethodsCount = SellerPaymentMethod::query()
            ->where('user_id', $sellerId)
            ->where('is_active', true)
            ->count();

        $recentOrders = Order::query()
            ->with(['buyer', 'items'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->limit(5)
            ->get();

        $seller = Auth::user();
        $unsettledAdminFee = $seller->unsettledAdminFeeTotal();
        $isOverFeeLimit = $seller->isOverUnpaidFeeLimit();

        return view('livewire.seller.dashboard-page', [
            'activeProductsCount' => $activeProductsCount,
            'pendingProductsCount' => $pendingProductsCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'completedRevenue' => $completedRevenue,
            'activePaymentMethodsCount' => $activePaymentMethodsCount,
            'unsettledAdminFee' => $unsettledAdminFee,
            'isOverFeeLimit' => $isOverFeeLimit,
            'recentOrders' => $recentOrders,
        ]);
    }
}
