<?php

namespace App\Services;

use App\Settings\AboutUsSetting;
use App\Settings\HomepageSetting;
use App\Support\LocalizedValue;

class HomepageSettingService
{
    public function __construct(
        private HomepageSetting $homepageSetting,
        private AboutUsSetting $aboutUsSetting,
    ) {}

    public function formData(): array
    {
        return [
            'brand_name_ar' => $this->homepageSetting->brand_name_ar,
            'brand_name_en' => $this->homepageSetting->brand_name_en,
            'about_title_ar' => $this->homepageSetting->about_title_ar,
            'about_title_en' => $this->homepageSetting->about_title_en,
            'about_body_ar' => $this->homepageSetting->about_body_ar,
            'about_body_en' => $this->homepageSetting->about_body_en,
            'partners_title_ar' => $this->homepageSetting->partners_title_ar,
            'partners_title_en' => $this->homepageSetting->partners_title_en,
            'why_title_ar' => $this->homepageSetting->why_title_ar,
            'why_title_en' => $this->homepageSetting->why_title_en,
            'why_lead_ar' => $this->homepageSetting->why_lead_ar,
            'why_lead_en' => $this->homepageSetting->why_lead_en,
            'services_title_ar' => $this->homepageSetting->services_title_ar,
            'services_title_en' => $this->homepageSetting->services_title_en,
            'works_title_ar' => $this->homepageSetting->works_title_ar,
            'works_title_en' => $this->homepageSetting->works_title_en,
            'contact_title_ar' => $this->homepageSetting->contact_title_ar,
            'contact_title_en' => $this->homepageSetting->contact_title_en,
            'meta_title_ar' => $this->homepageSetting->meta_title_ar,
            'meta_title_en' => $this->homepageSetting->meta_title_en,
            'meta_description_ar' => $this->homepageSetting->meta_description_ar,
            'meta_description_en' => $this->homepageSetting->meta_description_en,
        ];
    }

    public function update(array $data): void
    {
        foreach ($this->formData() as $key => $unused) {
            if (array_key_exists($key, $data)) {
                $this->homepageSetting->{$key} = $data[$key];
            }
        }

        $this->homepageSetting->save();
        $this->syncAboutUs();
    }

    public function localized(string $arabicProperty, string $englishProperty): ?string
    {
        return LocalizedValue::pick(
            $this->homepageSetting->{$arabicProperty},
            $this->homepageSetting->{$englishProperty},
        );
    }

    private function syncAboutUs(): void
    {
        $this->aboutUsSetting->value_ar = $this->homepageSetting->about_body_ar;
        $this->aboutUsSetting->value_en = $this->homepageSetting->about_body_en;
        $this->aboutUsSetting->save();
    }
}
