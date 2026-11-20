<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'provider_name', 'account_number', 'account_holder', 'instructions', 'is_active'])]
class SellerPaymentMethod extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toSnapshot(): array
    {
        return [
            'provider_name' => $this->provider_name,
            'account_number' => $this->account_number,
            'account_holder' => $this->account_holder,
            'instructions' => $this->instructions,
        ];
    }
}
