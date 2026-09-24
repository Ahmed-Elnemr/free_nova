<?php

namespace App\Support;

final class LocalizedValue
{
    public static function pick(?string $arabic, ?string $english, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        $primary = $locale === 'ar' ? $arabic : $english;
        $fallback = $locale === 'ar' ? $english : $arabic;

        return filled($primary) ? $primary : $fallback;
    }
}
