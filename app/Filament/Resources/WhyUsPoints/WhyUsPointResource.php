<?php

namespace App\Filament\Resources\WhyUsPoints;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\WhyUsPoints\Pages\ManageWhyUsPoints;
use App\Filament\Schemas\ContentFields;
use App\Models\WhyUsPoint;
use App\Services\WhyUsPointService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class WhyUsPointResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = WhyUsPoint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 3;

    protected static function permissionPrefix(): string
    {
        return 'why_us_points';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.why_us_points');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.why_us_point');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.why_us_points');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ...ContentFields::translated('body', true),
            ...ContentFields::publishing(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('body')
                    ->label(__('filament.fields.body'))
                    ->limit(80)
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
                    return app(WhyUsPointService::class)->update($record->getKey(), $data);
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
            'index' => ManageWhyUsPoints::route('/'),
        ];
    }
}
