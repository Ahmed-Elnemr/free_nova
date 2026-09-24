<?php

namespace App\Filament\Resources\PortfolioWorks;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\PortfolioWorks\Pages\ManagePortfolioWorks;
use App\Filament\Schemas\ContentFields;
use App\Models\PortfolioWork;
use App\Services\PortfolioWorkService;
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

class PortfolioWorkResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = PortfolioWork::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 5;

    protected static function permissionPrefix(): string
    {
        return 'portfolio_works';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.works');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.work');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.works');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ...ContentFields::translated('title'),
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
                    ->getStateUsing(fn (PortfolioWork $record) => $record->getFirstMediaUrl('image') ?: null),
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
                    return app(PortfolioWorkService::class)->update($record->getKey(), $data);
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
            'index' => ManagePortfolioWorks::route('/'),
        ];
    }
}
