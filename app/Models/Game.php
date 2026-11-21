<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'short_code', 'cover_path', 'description', 'sort_order'])]
class Game extends Model
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function coverUrl(): string
    {
        if ($this->cover_path) {
            if (str_starts_with($this->cover_path, 'assets/')) {
                return asset($this->cover_path);
            }

            return asset('storage/'.$this->cover_path);
        }

        return asset('assets/game-covers/open-world.svg');
    }
}
