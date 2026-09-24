<?php

namespace App\Filament\Resources\ServiceItems;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\ServiceItems\Pages\ManageServiceItems;
use App\Filament\Schemas\ContentFields;
use App\Models\ServiceItem;
use App\Services\ServiceItemService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ServiceItemResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = ServiceItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?int $navigationSort = 4;

    protected static function permissionPrefix(): string
    {
        return 'service_items';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.services');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.services');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ...ContentFields::translated('title'),
            ...ContentFields::translated('description', true),
            ...ContentFields::publishing(),
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
                    ->getStateUsing(fn (ServiceItem $record) => $record->getFirstMediaUrl('image') ?: null),
                TextColumn::make('title')
                    ->label(__('filament.fields.title'))
                    ->searchable(),
                TextColumn::make('sort_order')
                    ->label(__('filament.fields.sort_order'))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('filament.fields.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make()->using(function (Model $record, array $data) {
                    return app(ServiceItemService::class)->update($record->getKey(), $data);
                }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageServiceItems::route('/'),
        ];
    }
}
