<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

abstract class BaseSettingsPage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    abstract protected function settingComponents(): array;

    abstract protected function loadFormData(): array;

    abstract protected function persist(array $data): void;

    abstract protected static function permissionName(): string;

    public function mount(): void
    {
        $this->form->fill($this->loadFormData());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components($this->settingComponents())
            ->columns($this->formColumns())
            ->statePath('data');
    }

    protected function formColumns(): int
    {
        return 1;
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label(__('filament.actions.save'))
                            ->submit('save')
                            ->visible(fn (): bool => $this->canUpdate()),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        abort_unless($this->canUpdate(), 403);

        $this->persist($this->form->getState());

        Notification::make()
            ->title(__('filament.saved'))
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        $user = filament()->auth()->user();

        if (! $user) {
            return false;
        }

        $separator = config('permission.separator', ':');

        return $user->can(static::permissionName().$separator.'read');
    }

    protected function canUpdate(): bool
    {
        $user = filament()->auth()->user();
        $separator = config('permission.separator', ':');

        return (bool) $user?->can(static::permissionName().$separator.'update');
    }
}
