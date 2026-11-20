<?php

namespace App\Policies;

use App\Models\SellerPaymentMethod;
use App\Models\User;

class SellerPaymentMethodPolicy
{
    public function manage(User $user, SellerPaymentMethod $method): bool
    {
        return $user->isSeller() && $method->user_id === $user->id;
    }
}
