<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomepageSetting extends Settings
{
    public ?string $brand_name_ar;

    public ?string $brand_name_en;

    public ?string $about_title_ar;

    public ?string $about_title_en;

    public ?string $about_body_ar;

    public ?string $about_body_en;

    public ?string $partners_title_ar;

    public ?string $partners_title_en;

    public ?string $why_title_ar;

    public ?string $why_title_en;

    public ?string $why_lead_ar;

    public ?string $why_lead_en;

    public ?string $services_title_ar;

    public ?string $services_title_en;

    public ?string $works_title_ar;

    public ?string $works_title_en;

    public ?string $contact_title_ar;

    public ?string $contact_title_en;

    public ?string $meta_title_ar;

    public ?string $meta_title_en;

    public ?string $meta_description_ar;

    public ?string $meta_description_en;

    public static function group(): string
    {
        return 'homepage';
    }
}
