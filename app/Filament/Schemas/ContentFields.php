<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;

final class ContentFields
{
    public static function translated(string $attribute, bool $long = false): array
    {
        $component = $long ? Textarea::class : TextInput::class;

        $fields = [
            $component::make($attribute.'.ar')
                ->label(__('filament.fields.localized', [
                    'field' => __("filament.fields.$attribute"),
                    'locale' => __('filament.locales.ar'),
                ]))
                ->required(),
            $component::make($attribute.'.en')
                ->label(__('filament.fields.localized', [
                    'field' => __("filament.fields.$attribute"),
                    'locale' => __('filament.locales.en'),
                ]))
                ->required(),
        ];

        if ($long) {
            $fields[0]->rows(4)->columnSpanFull();
            $fields[1]->rows(4)->columnSpanFull();
        }

        return $fields;
    }

    public static function settingPair(string $key, bool $long = false): array
    {
        $component = $long ? Textarea::class : TextInput::class;
        $label = __("filament.fields.$key");

        $fields = [
            $component::make($key.'_ar')
                ->label(__('filament.fields.localized', [
                    'field' => $label,
                    'locale' => __('filament.locales.ar'),
                ]))
                ->required(),
            $component::make($key.'_en')
                ->label(__('filament.fields.localized', [
                    'field' => $label,
                    'locale' => __('filament.locales.en'),
                ]))
                ->required(),
        ];

        if ($long) {
            $fields[0]->rows(5)->columnSpanFull();
            $fields[1]->rows(5)->columnSpanFull();
        }

        return $fields;
    }

    public static function publishing(bool $slug = true): array
    {
        $fields = [];

        if ($slug) {
            $fields[] = TextInput::make('slug')
                ->label(__('filament.fields.slug'))
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true);
        }

        $fields[] = TextInput::make('sort_order')
            ->label(__('filament.fields.sort_order'))
            ->numeric()
            ->default(0)
            ->required();

        $fields[] = Toggle::make('is_active')
            ->label(__('filament.fields.is_active'))
            ->default(true);

        return $fields;
    }

    public static function upload(string $name, bool $imagesOnly = true): FileUpload
    {
        $field = FileUpload::make($name)
            ->label(__('filament.fields.'.$name))
            ->disk('public')
            ->directory('filament-uploads')
            ->maxSize(8192)
            ->columnSpanFull();

        if ($imagesOnly) {
            return $field->image();
        }

        return $field->acceptedFileTypes([
            'image/png',
            'image/jpeg',
            'image/webp',
            'image/svg+xml',
        ]);
    }

    public static function currentMedia(string $collection): Placeholder
    {
        return Placeholder::make('current_'.$collection)
            ->label(__('filament.fields.current_image'))
            ->content(function ($record) use ($collection) {
                $url = $record?->getFirstMediaUrl($collection);

                if (! $url) {
                    return __('filament.fields.no_image');
                }

                return new HtmlString('<img src="'.e($url).'" alt="" style="max-height:88px;border-radius:8px" />');
            })
            ->visible(fn ($record) => filled($record))
            ->columnSpanFull();
    }

    public static function section(string $title, array $fields): Section
    {
        return Section::make($title)->schema($fields)->columns(2);
    }
}
