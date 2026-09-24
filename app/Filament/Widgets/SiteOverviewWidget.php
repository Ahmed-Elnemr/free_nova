<?php

namespace App\Filament\Widgets;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\PortfolioWorks\PortfolioWorkResource;
use App\Filament\Resources\ServiceItems\ServiceItemResource;
use App\Repositories\Contracts\ContactMessageRepositoryContract;
use App\Repositories\Contracts\PartnerRepositoryContract;
use App\Repositories\Contracts\PortfolioWorkRepositoryContract;
use App\Repositories\Contracts\ServiceItemRepositoryContract;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            $this->publishedStat(
                __('filament.stats.partners'),
                app(PartnerRepositoryContract::class),
                PartnerResource::getUrl(),
                Heroicon::OutlinedBuildingOffice2,
            ),
            $this->publishedStat(
                __('filament.stats.services'),
                app(ServiceItemRepositoryContract::class),
                ServiceItemResource::getUrl(),
                Heroicon::OutlinedWrenchScrewdriver,
            ),
            $this->publishedStat(
                __('filament.stats.works'),
                app(PortfolioWorkRepositoryContract::class),
                PortfolioWorkResource::getUrl(),
                Heroicon::OutlinedPhoto,
            ),
            $this->inboxStat(),
        ];
    }

    private function publishedStat(
        string $label,
        PartnerRepositoryContract|ServiceItemRepositoryContract|PortfolioWorkRepositoryContract $repository,
        string $url,
        Heroicon $icon,
    ): Stat {
        $active = $repository->countActive();
        $hidden = max(0, $repository->count() - $active);

        return Stat::make($label, $active)
            ->description(
                $hidden > 0
                    ? __('filament.stats.hidden', ['count' => $hidden])
                    : __('filament.stats.on_site')
            )
            ->descriptionIcon($hidden > 0 ? Heroicon::OutlinedEyeSlash : Heroicon::OutlinedEye)
            ->descriptionColor($hidden > 0 ? 'warning' : 'gray')
            ->icon($icon)
            ->color('primary')
            ->url($url);
    }

    private function inboxStat(): Stat
    {
        $messages = app(ContactMessageRepositoryContract::class);
        $waiting = $messages->countByStatus(ContactMessageStatus::NOT_REPLITED);

        return Stat::make(__('filament.stats.unread_messages'), $waiting)
            ->description($waiting > 0 ? __('filament.stats.needs_reply') : __('filament.stats.all_clear'))
            ->descriptionIcon($waiting > 0 ? Heroicon::OutlinedClock : Heroicon::OutlinedCheckCircle)
            ->descriptionColor($waiting > 0 ? 'warning' : 'success')
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->color($waiting > 0 ? 'warning' : 'success')
            ->url(ContactMessageResource::getUrl());
    }
}
