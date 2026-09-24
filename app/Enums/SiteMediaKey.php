<?php

namespace App\Enums;

enum SiteMediaKey: string
{
    case Hero = 'hero';
    case WhyUs = 'why_us';

    public function label(): string
    {
        return match ($this) {
            self::Hero => __('filament.site_media.hero'),
            self::WhyUs => __('filament.site_media.why_us'),
        };
    }
}
