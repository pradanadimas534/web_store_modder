<?php

namespace App\Livewire\Account;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Pesanan & Unduhan Saya')]
class OrdersPage extends Component
{
    use WithPagination;

    public function render(): View
    {
        $orders = Order::query()
            ->with(['items.product', 'seller.sellerProfile'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->paginate(10);

        $downloadLinks = [];

        foreach ($orders as $order) {
            if ($order->status === OrderStatus::Completed) {
                foreach ($order->items as $item) {
                    $downloadLinks[$item->id] = URL::temporarySignedRoute(
                        'downloads.paid',
                        now()->plus(minutes: 15),
                        ['orderItem' => $item->id]
                    );
                }
            }
        }

        return view('livewire.account.orders-page', [
            'orders' => $orders,
            'downloadLinks' => $downloadLinks,
        ]);
    }
}
