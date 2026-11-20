<?php

namespace App\Livewire\Admin;

use App\Enums\AdPaymentStatus;
use App\Enums\AdStatus;
use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Enums\SellerSettlementStatus;
use App\Enums\UserRole;
use App\Models\Ad;
use App\Models\Order;
use App\Models\Product;
use App\Models\SellerFeeSettlement;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Admin Dashboard')]
class DashboardPage extends Component
{
    public function render(): View
    {
        $settledOrderFeeRevenue = (int) SellerFeeSettlement::query()
            ->where('status', SellerSettlementStatus::Verified)
            ->sum('amount');

        $unsettledOrderFeeReceivable = (int) Order::query()
            ->where('status', OrderStatus::Completed)
            ->whereNull('fee_settlement_id')
            ->sum('admin_fee');

        $pendingSettlementsCount = SellerFeeSettlement::query()
            ->where('status', SellerSettlementStatus::PendingVerification)
            ->count();

        $platformAdRevenue = (int) Ad::query()
            ->where('payment_status', AdPaymentStatus::Paid)
            ->sum('total_price');

        return view('livewire.admin.dashboard-page', [
            'totalSales' => (int) Order::query()->where('status', OrderStatus::Completed)->sum('total'),
            'settledOrderFeeRevenue' => $settledOrderFeeRevenue,
            'unsettledOrderFeeReceivable' => $unsettledOrderFeeReceivable,
            'pendingSettlementsCount' => $pendingSettlementsCount,
            'platformAdRevenue' => $platformAdRevenue,
            'totalPlatformRevenue' => $settledOrderFeeRevenue + $platformAdRevenue,
            'completedOrdersCount' => Order::query()->where('status', OrderStatus::Completed)->count(),
            'pendingProductsCount' => Product::query()->where('status', ProductStatus::PendingReview)->count(),
            'activeProductsCount' => Product::query()->where('status', ProductStatus::Active)->count(),
            'sellersCount' => User::query()->where('role', UserRole::Seller)->count(),
            'buyersCount' => User::query()->where('role', UserRole::User)->count(),
            'pendingAdsCount' => Ad::query()->where('status', AdStatus::Pending)->count(),
            'activeAdsCount' => Ad::query()->where('status', AdStatus::Active)->count(),
            'recentOrders' => Order::query()->with(['buyer', 'seller.sellerProfile'])->latest()->limit(5)->get(),
        ]);
    }
}
