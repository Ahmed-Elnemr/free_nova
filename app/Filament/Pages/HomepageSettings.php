<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\ContentFields;
use App\Services\HomepageSettingService;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class HomepageSettings extends BaseSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'homepage-settings';

    public static function getNavigationLabel(): string
    {
        return __('filament.pages.homepage');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.pages.homepage');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.settings');
    }

    protected static function permissionName(): string
    {
        return 'homepage_settings';
    }

    protected function settingComponents(): array
    {
        return [
            ContentFields::section(__('filament.sections.brand'), [
                ...ContentFields::settingPair('brand_name'),
            ]),
            ContentFields::section(__('filament.sections.about'), [
                ...ContentFields::settingPair('about_title'),
                ...ContentFields::settingPair('about_body', true),
            ]),
            ContentFields::section(__('filament.sections.partners'), [
                ...ContentFields::settingPair('partners_title'),
            ]),
            ContentFields::section(__('filament.sections.why_us'), [
                ...ContentFields::settingPair('why_title'),
                ...ContentFields::settingPair('why_lead', true),
            ]),
            ContentFields::section(__('filament.sections.services'), [
                ...ContentFields::settingPair('services_title'),
            ]),
            ContentFields::section(__('filament.sections.works'), [
                ...ContentFields::settingPair('works_title'),
            ]),
            ContentFields::section(__('filament.sections.contact'), [
                ...ContentFields::settingPair('contact_title'),
            ]),
            ContentFields::section(__('filament.sections.seo'), [
                ...ContentFields::settingPair('meta_title'),
                ...ContentFields::settingPair('meta_description', true),
            ]),
        ];
    }

    protected function loadFormData(): array
    {
        return app(HomepageSettingService::class)->formData();
    }

    protected function persist(array $data): void
    {
        app(HomepageSettingService::class)->update($data);
    }
}
