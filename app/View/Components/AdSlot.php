<?php

namespace App\View\Components;

use App\Enums\AdPlacement;
use App\Models\Ad;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdSlot extends Component
{
    public ?Ad $ad = null;

    public AdPlacement $placementEnum;

    public function __construct(public string $placement)
    {
        $this->placementEnum = AdPlacement::tryFrom($placement) ?? AdPlacement::CatalogBanner;

        $this->ad = Ad::query()
            ->activeForPlacement($this->placementEnum)
            ->inRandomOrder()
            ->first();

        if ($this->ad) {
            $this->ad->increment('impressions_count');
        }
    }

    public function render(): View
    {
        return view('components.ad-slot');
    }
}
