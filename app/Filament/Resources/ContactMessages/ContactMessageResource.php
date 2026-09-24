<?php

namespace App\Filament\Resources\ContactMessages;

use App\Enums\ContactMessageStatus;
use App\Enums\ContactMessageType;
use App\Filament\Concerns\AuthorizesFilamentResource;
use App\Filament\Resources\ContactMessages\Pages\ManageContactMessages;
use App\Models\ContactMessage;
use App\Repositories\Contracts\ContactMessageRepositoryContract;
use App\Services\ContactMessageService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ContactMessageResource extends Resource
{
    use AuthorizesFilamentResource;

    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'contact_messages';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.models.contact_messages');
    }

    public static function getModelLabel(): string
    {
        return __('filament.models.contact_message');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.models.contact_messages');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.groups.inbox');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = app(ContactMessageRepositoryContract::class)
            ->countByStatus(ContactMessageStatus::NOT_REPLITED);

        return $count > 0 ? (string) $count : null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label(__('filament.fields.name')),
            TextInput::make('phone')->label(__('filament.fields.phone')),
            TextInput::make('email')->label(__('filament.fields.email')),
            Select::make('message_type')
                ->label(__('filament.fields.message_type'))
                ->options(collect(ContactMessageType::cases())->mapWithKeys(
                    fn (ContactMessageType $type) => [$type->value => $type->label()]
                )),
            Select::make('status')
                ->label(__('filament.fields.status'))
                ->options(collect(ContactMessageStatus::cases())->mapWithKeys(
                    fn (ContactMessageStatus $status) => [$status->value => $status->label()]
                )),
            Textarea::make('message')->label(__('filament.fields.message'))->columnSpanFull(),
            Textarea::make('reply')->label(__('filament.fields.reply'))->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('filament.fields.name'))->searchable(),
                TextColumn::make('phone')->label(__('filament.fields.phone')),
                TextColumn::make('email')->label(__('filament.fields.email'))->searchable(),
                TextColumn::make('message_type')
                    ->label(__('filament.fields.message_type'))
                    ->formatStateUsing(fn (ContactMessageType|string|null $state) => $state instanceof ContactMessageType ? $state->label() : (string) $state),
                TextColumn::make('status')
                    ->label(__('filament.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (ContactMessageStatus|string|null $state) => $state instanceof ContactMessageStatus ? $state->label() : (string) $state),
                TextColumn::make('created_at')
                    ->label(__('filament.fields.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.fields.status'))
                    ->options(collect(ContactMessageStatus::cases())->mapWithKeys(
                        fn (ContactMessageStatus $status) => [$status->value => $status->label()]
                    )),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('reply')
                    ->label(__('filament.actions.reply'))
                    ->icon(Heroicon::OutlinedPaperAirplane)
                    ->visible(fn (ContactMessage $record) => static::canPermission('update') && $record->status !== ContactMessageStatus::REPLIED)
                    ->form([
                        Textarea::make('reply')
                            ->label(__('filament.fields.reply'))
                            ->required(),
                    ])
                    ->action(function (ContactMessage $record, array $data): void {
                        app(ContactMessageService::class)->reply($record->id, $data['reply']);
                    }),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageContactMessages::route('/'),
        ];
    }
}
