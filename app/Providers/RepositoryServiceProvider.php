<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CommentRepository;
use App\Repositories\ContactMessageRepository;
use App\Repositories\Contracts\AdminRepositoryContract;
use App\Repositories\Contracts\BlogRepositoryContract;
use App\Repositories\Contracts\CommentRepositoryContract;
use App\Repositories\Contracts\ContactMessageRepositoryContract;
use App\Repositories\Contracts\FcmTokenRepositoryContract;
use App\Repositories\Contracts\NotificationGroupRepositoryContract;
use App\Repositories\Contracts\PartnerRepositoryContract;
use App\Repositories\Contracts\PortfolioWorkRepositoryContract;
use App\Repositories\Contracts\RoleRepositoryContract;
use App\Repositories\Contracts\ServiceItemRepositoryContract;
use App\Repositories\Contracts\SiteMediaRepositoryContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Contracts\WhyUsPointRepositoryContract;
use App\Repositories\FcmTokenRepository;
use App\Repositories\NotificationGroupRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\PortfolioWorkRepository;
use App\Repositories\RoleRepository;
use App\Repositories\ServiceItemRepository;
use App\Repositories\SiteMediaRepository;
use App\Repositories\UserRepository;
use App\Repositories\WhyUsPointRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
        $this->app->bind(AdminRepositoryContract::class, AdminRepository::class);
        $this->app->bind(ContactMessageRepositoryContract::class, ContactMessageRepository::class);
        $this->app->bind(NotificationGroupRepositoryContract::class, NotificationGroupRepository::class);
        $this->app->bind(BlogRepositoryContract::class, BlogRepository::class);
        $this->app->bind(CommentRepositoryContract::class, CommentRepository::class);
        $this->app->bind(FcmTokenRepositoryContract::class, FcmTokenRepository::class);
        $this->app->bind(PartnerRepositoryContract::class, PartnerRepository::class);
        $this->app->bind(WhyUsPointRepositoryContract::class, WhyUsPointRepository::class);
        $this->app->bind(ServiceItemRepositoryContract::class, ServiceItemRepository::class);
        $this->app->bind(PortfolioWorkRepositoryContract::class, PortfolioWorkRepository::class);
        $this->app->bind(SiteMediaRepositoryContract::class, SiteMediaRepository::class);
    }
}
