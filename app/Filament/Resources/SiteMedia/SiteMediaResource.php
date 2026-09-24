<?php

namespace App\Filament\Resources\SiteMedia;

use App\Enums\SiteMediaKey;
use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\SiteMedia\Pages\ManageSiteMedia;
use App\Filament\Schemas\ContentFields;
use App\Models\SiteMedia;
use App\Services\SiteMediaService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SiteMediaResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = SiteMedia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'site_media';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.site_media');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.site_media_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.site_media');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('key')
                ->label(__('filament.fields.key'))
                ->options(collect(SiteMediaKey::cases())->mapWithKeys(
                    fn (SiteMediaKey $key) => [$key->value => $key->label()]
                ))
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn (?SiteMedia $record) => filled($record))
                ->dehydrated(),
            ...ContentFields::translated('alt'),
            Toggle::make('is_active')
                ->label(__('filament.fields.is_active'))
                ->default(true),
            ContentFields::currentMedia('image'),
            ContentFields::upload('image'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('filament.fields.image'))
                    ->getStateUsing(fn (SiteMedia $record) => $record->getFirstMediaUrl('image') ?: null),
                TextColumn::make('key')
                    ->label(__('filament.fields.key'))
                    ->formatStateUsing(fn (SiteMediaKey|string|null $state) => $state instanceof SiteMediaKey ? $state->label() : (string) $state),
                TextColumn::make('alt')
                    ->label(__('filament.fields.alt')),
                IconColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make()->using(function (Model $record, array $data) {
                    return app(SiteMediaService::class)->update($record->getKey(), $data);
                }),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteMedia::route('/'),
        ];
    }
}
