<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            $root = request()->getSchemeAndHttpHost();

            config([
                'filesystems.disks.public.url' => $root.'/storage',
                'filesystems.disks.app.url' => $root.'/app',
            ]);
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar', 'en'])
                ->labels([
                    'ar' => 'العربية',
                    'en' => 'English',
                ])
                ->visible(outsidePanels: true)
                ->renderHook('panels::user-menu.before')
                ->userPreferredLocale('ar');
        });
    }
}
