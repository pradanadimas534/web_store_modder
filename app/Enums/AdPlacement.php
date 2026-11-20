<?php

namespace App\Enums;

enum AdPlacement: string
{
    case CatalogBanner = 'catalog_banner';
    case SidebarPromo = 'sidebar_promo';
    case FooterStrip = 'footer_strip';

    public function label(): string
    {
        return match ($this) {
            self::CatalogBanner => 'Banner Katalog (Horizontal)',
            self::SidebarPromo => 'Kartu Promo Sidebar',
            self::FooterStrip => 'Strip Promo Footer',
        };
    }
}
