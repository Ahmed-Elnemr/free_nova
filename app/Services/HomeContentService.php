<?php

namespace App\Services;

use App\Enums\SiteMediaKey;
use App\Http\Resources\Client\PartnerResource;
use App\Http\Resources\Client\PortfolioWorkResource;
use App\Http\Resources\Client\ServiceItemResource;
use App\Http\Resources\Client\SiteMediaResource;
use App\Http\Resources\Client\WhyUsPointResource;
use App\Models\SiteMedia;
use App\Repositories\Contracts\PartnerRepositoryContract;
use App\Repositories\Contracts\PortfolioWorkRepositoryContract;
use App\Repositories\Contracts\ServiceItemRepositoryContract;
use App\Repositories\Contracts\SiteMediaRepositoryContract;
use App\Repositories\Contracts\WhyUsPointRepositoryContract;

class HomeContentService
{
    public function __construct(
        private HomepageSettingService $homepageSettingService,
        private ContactSettingService $contactSettingService,
        private PartnerRepositoryContract $partners,
        private WhyUsPointRepositoryContract $whyUsPoints,
        private ServiceItemRepositoryContract $serviceItems,
        private PortfolioWorkRepositoryContract $works,
        private SiteMediaRepositoryContract $siteMedia,
    ) {}

    public function homepage(): array
    {
        return [
            'brand' => [
                'name' => $this->homepageSettingService->localized('brand_name_ar', 'brand_name_en'),
            ],
            'hero' => $this->mediaPayload(SiteMediaKey::Hero),
            'about' => [
                'title' => $this->homepageSettingService->localized('about_title_ar', 'about_title_en'),
                'body' => $this->homepageSettingService->localized('about_body_ar', 'about_body_en'),
            ],
            'partners' => [
                'title' => $this->homepageSettingService->localized('partners_title_ar', 'partners_title_en'),
                'items' => PartnerResource::collection($this->partners->activeOrdered(withMedia: true))->resolve(),
            ],
            'why_us' => [
                'title' => $this->homepageSettingService->localized('why_title_ar', 'why_title_en'),
                'lead' => $this->homepageSettingService->localized('why_lead_ar', 'why_lead_en'),
                'image' => $this->mediaPayload(SiteMediaKey::WhyUs),
                'points' => WhyUsPointResource::collection($this->whyUsPoints->activeOrdered())->resolve(),
            ],
            'services' => [
                'title' => $this->homepageSettingService->localized('services_title_ar', 'services_title_en'),
                'items' => ServiceItemResource::collection($this->serviceItems->activeOrdered(withMedia: true))->resolve(),
            ],
            'works' => [
                'title' => $this->homepageSettingService->localized('works_title_ar', 'works_title_en'),
                'items' => PortfolioWorkResource::collection($this->works->activeOrdered(withMedia: true))->resolve(),
            ],
            'contact' => array_merge(
                ['title' => $this->homepageSettingService->localized('contact_title_ar', 'contact_title_en')],
                $this->contactSettingService->getSettings(),
            ),
            'seo' => [
                'title' => $this->homepageSettingService->localized('meta_title_ar', 'meta_title_en'),
                'description' => $this->homepageSettingService->localized('meta_description_ar', 'meta_description_en'),
            ],
        ];
    }

    private function mediaPayload(SiteMediaKey $key): ?array
    {
        $media = $this->siteMedia->findByKey($key);

        if (! $media instanceof SiteMedia) {
            return null;
        }

        return (new SiteMediaResource($media))->resolve();
    }
}
