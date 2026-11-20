<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin()
            || $order->buyer_id === $user->id
            || $order->seller_id === $user->id;
    }

    public function uploadProof(User $user, Order $order): bool
    {
        return $order->buyer_id === $user->id && $order->canUploadProof();
    }

    public function verify(User $user, Order $order): bool
    {
        return $user->isAdmin() || ($user->isSeller() && $order->seller_id === $user->id);
    }
}
