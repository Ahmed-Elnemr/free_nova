<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\Roles\Pages\ManageRoles;
use App\Filament\Schemas\ContentFields;
use App\Models\Permission;
use App\Models\Role;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class RoleResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'roles';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.roles');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.roles');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.access');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('guard_name', 'admin');
    }

    public static function canDelete(Model $record): bool
    {
        return static::canPermission('delete')
            && $record->getTranslation('name', 'en') !== 'Super Admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ...ContentFields::translated('name'),
            Hidden::make('guard_name')->default('admin'),
            Toggle::make('is_active')
                ->label(__('filament.fields.is_active'))
                ->default(true),
            CheckboxList::make('permissions')
                ->label(__('filament.fields.permissions'))
                ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'admin'))
                ->getOptionLabelFromRecordUsing(fn (Permission $record) => $record->name)
                ->columns(2)
                ->searchable()
                ->bulkToggleable()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('filament.fields.name'))->searchable(),
                IconColumn::make('is_active')->label(__('filament.fields.is_active'))->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRoles::route('/'),
        ];
    }
}
