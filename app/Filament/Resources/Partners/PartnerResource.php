<?php

namespace App\Filament\Resources\Partners;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\Partners\Pages\ManagePartners;
use App\Filament\Schemas\ContentFields;
use App\Models\Partner;
use App\Services\PartnerService;
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

class PartnerResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = Partner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'partners';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.partners');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.partner');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.partners');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ...ContentFields::translated('name'),
            ...ContentFields::publishing(),
            ContentFields::currentMedia('logo'),
            ContentFields::upload('logo', false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label(__('filament.fields.logo'))
                    ->getStateUsing(fn (Partner $record) => $record->getFirstMediaUrl('logo') ?: null),
                TextColumn::make('name')
                    ->label(__('filament.fields.name'))
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
                    return app(PartnerService::class)->update($record->getKey(), $data);
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
            'index' => ManagePartners::route('/'),
        ];
    }
}
