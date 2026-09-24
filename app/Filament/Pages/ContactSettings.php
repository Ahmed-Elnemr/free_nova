<?php

namespace App\Filament\Pages;

use App\Services\ContactSettingService;
use BackedEnum;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class ContactSettings extends BaseSettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'contact-settings';

    public static function getNavigationLabel(): string
    {
        return __('filament.pages.contact');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.pages.contact');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.settings');
    }

    protected static function permissionName(): string
    {
        return 'contact_settings';
    }

    protected function formColumns(): int
    {
        return 2;
    }

    protected function settingComponents(): array
    {
        return [
            TextInput::make('whatsapp_number')
                ->label(__('filament.fields.whatsapp_number'))
                ->tel(),
            TextInput::make('facebook_link')
                ->label(__('filament.fields.facebook_link'))
                ->url(),
            TextInput::make('instagram_link')
                ->label(__('filament.fields.instagram_link'))
                ->url(),
            TextInput::make('x_link')
                ->label(__('filament.fields.x_link'))
                ->url(),
            TextInput::make('youtube_link')
                ->label(__('filament.fields.youtube_link'))
                ->url(),
            TextInput::make('tiktok_link')
                ->label(__('filament.fields.tiktok_link'))
                ->url(),
            TextInput::make('snapchat_link')
                ->label(__('filament.fields.snapchat_link'))
                ->url(),
            TagsInput::make('contact_numbers')
                ->label(__('filament.fields.contact_numbers'))
                ->columnSpanFull(),
        ];
    }

    protected function loadFormData(): array
    {
        return app(ContactSettingService::class)->getSettings();
    }

    protected function persist(array $data): void
    {
        unset($data['whatsapp_link']);

        app(ContactSettingService::class)->updateFromArray($data);
    }
}
