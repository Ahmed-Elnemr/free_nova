<?php

namespace App\Filament\Resources\Admins;

use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\Admins\Pages\ManageAdmins;
use App\Models\Admin;
use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Services\AdminService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use UnitEnum;

class AdminResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = Admin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'admins';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.admins');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.admin');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.admins');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.access');
    }

    public static function canDelete(Model $record): bool
    {
        return static::canPermission('delete') && filament()->auth()->id() !== $record->getKey();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('filament.fields.name'))
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label(__('filament.fields.email'))
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('phone')
                ->label(__('filament.fields.phone'))
                ->tel(),
            TextInput::make('password')
                ->label(__('filament.fields.password'))
                ->password()
                ->revealable()
                ->dehydrated(fn (?string $state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create'),
            Select::make('role_id')
                ->label(__('filament.fields.role'))
                ->options(fn () => app(RoleRepositoryContract::class)
                    ->forGuard('admin')
                    ->mapWithKeys(fn (Role $role) => [
                        $role->id => $role->getTranslation('name', app()->getLocale()),
                    ]))
                ->required(),
            Toggle::make('is_active')
                ->label(__('filament.fields.is_active'))
                ->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('filament.fields.name'))->searchable(),
                TextColumn::make('email')->label(__('filament.fields.email'))->searchable(),
                TextColumn::make('phone')->label(__('filament.fields.phone')),
                IconColumn::make('is_active')->label(__('filament.fields.is_active'))->boolean(),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, Admin $record): array {
                        $data['role_id'] = $record->roles->first()?->id;
                        unset($data['password']);

                        return $data;
                    })
                    ->using(function (Model $record, array $data) {
                        return app(AdminService::class)->update($record->getKey(), static::prepareAdminData($data));
                    }),
                DeleteAction::make(),
            ]);
    }

    public static function prepareAdminData(array $data): array
    {
        if (blank(Arr::get($data, 'password'))) {
            unset($data['password']);
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAdmins::route('/'),
        ];
    }
}
