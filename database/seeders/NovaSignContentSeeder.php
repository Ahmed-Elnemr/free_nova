<?php

namespace Database\Seeders;

use App\Enums\SiteMediaKey;
use App\Repositories\Contracts\PartnerRepositoryContract;
use App\Repositories\Contracts\PortfolioWorkRepositoryContract;
use App\Repositories\Contracts\ServiceItemRepositoryContract;
use App\Repositories\Contracts\SiteMediaRepositoryContract;
use App\Repositories\Contracts\WhyUsPointRepositoryContract;
use App\Services\ContactSettingService;
use App\Services\HomepageSettingService;
use App\Services\PartnerService;
use App\Services\PortfolioWorkService;
use App\Services\ServiceItemService;
use App\Services\SiteMediaService;
use App\Services\WhyUsPointService;
use App\Settings\ContactSetting;
use App\Settings\HomepageSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Throwable;

class NovaSignContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHomepage();
        $this->seedContact();
        $this->seedSiteMedia();
        $this->seedPartners();
        $this->seedWhyUs();
        $this->seedServices();
        $this->seedWorks();
    }

    private function seedHomepage(): void
    {
        $settings = app(HomepageSetting::class);

        if (filled($settings->brand_name_ar)) {
            return;
        }

        app(HomepageSettingService::class)->update([
            'brand_name_ar' => 'نوفا ساين',
            'brand_name_en' => 'Nova Sign',
            'about_title_ar' => 'نوفا ساين',
            'about_title_en' => 'Nova Sign',
            'about_body_ar' => 'شركة متخصصة في إيجاد الحلول الإعلانية المبتكرة حيث نقدم تجارب بصرية جذابة تعزز وجود علامات عملائنا التجارية في السوق معتمدين على خبرتنا الممتدة لأكثر من 25 عاماً باستخدام التقنيات الحديثة والنهج الإبداعي',
            'about_body_en' => 'A company specialized in innovative advertising solutions. We create compelling visual experiences that strengthen our clients’ brands in the market, drawing on more than 25 years of experience, modern technology, and a creative approach.',
            'partners_title_ar' => 'شركاء النجاح',
            'partners_title_en' => 'Success Partners',
            'why_title_ar' => 'لماذا نحن؟',
            'why_title_en' => 'Why Us?',
            'why_lead_ar' => 'لأننا نقدم مزيجاً فريداً يجمع بين خبرة 25 عاماً و التقنيات الحديثة',
            'why_lead_en' => 'Because we combine 25 years of experience with modern technology.',
            'services_title_ar' => 'خدماتنا',
            'services_title_en' => 'Our Services',
            'works_title_ar' => 'بعض من أعمالنا',
            'works_title_en' => 'Some of Our Work',
            'contact_title_ar' => 'تواصل معنا',
            'contact_title_en' => 'Contact Us',
            'meta_title_ar' => 'نوفا ساين | حلول إعلانية مبتكرة',
            'meta_title_en' => 'Nova Sign | Innovative Advertising Solutions',
            'meta_description_ar' => 'تصميم وتصنيع اللوحات والمجسمات والمطبوعات ولواصق السيارات والديكور والشاشات الإعلانية.',
            'meta_description_en' => 'Design and production of signs, sculptures, print, vehicle wraps, décor, and advertising screens.',
        ]);
    }

    private function seedContact(): void
    {
        $settings = app(ContactSetting::class);

        if (filled($settings->whatsapp_number)) {
            return;
        }

        app(ContactSettingService::class)->updateFromArray([
            'whatsapp_number' => '963960000880',
            'instagram_link' => 'https://www.instagram.com/nova.signads/',
            'facebook_link' => 'https://www.facebook.com/profile.php?id=61579615327357',
        ]);
    }

    private function seedSiteMedia(): void
    {
        $this->seedMediaRecord(
            repository: app(SiteMediaRepositoryContract::class),
            service: app(SiteMediaService::class),
            lookup: ['key', SiteMediaKey::Hero->value],
            data: [
                'key' => SiteMediaKey::Hero->value,
                'alt' => ['ar' => 'نوفا ساين', 'en' => 'Nova Sign'],
                'is_active' => true,
            ],
            url: 'https://novasign-sy.com/Photos/Landing.webp',
            collection: 'image',
        );

        $this->seedMediaRecord(
            repository: app(SiteMediaRepositoryContract::class),
            service: app(SiteMediaService::class),
            lookup: ['key', SiteMediaKey::WhyUs->value],
            data: [
                'key' => SiteMediaKey::WhyUs->value,
                'alt' => ['ar' => 'لماذا نحن', 'en' => 'Why us'],
                'is_active' => true,
            ],
            url: 'https://novasign-sy.com/Photos/Sings.webp',
            collection: 'image',
        );
    }

    private function seedPartners(): void
    {
        $items = [
            ['slug' => 'al-saada', 'name' => ['ar' => 'مجوهرات السعادة', 'en' => 'Al Saada Jewelry'], 'url' => 'https://novasign-sy.com/Photos/al%20saada.svg'],
            ['slug' => 'biscoza', 'name' => ['ar' => 'بيسكوزا', 'en' => 'Biscoza'], 'url' => 'https://novasign-sy.com/Photos/biscouza.svg'],
            ['slug' => 'dar-amer', 'name' => ['ar' => 'دار عامر', 'en' => 'Dar Amer'], 'url' => 'https://novasign-sy.com/Photos/dar%20amer.svg'],
            ['slug' => 'jadever', 'name' => ['ar' => 'جيديفر', 'en' => 'Jadever'], 'url' => 'https://novasign-sy.com/Photos/jadever.svg'],
            ['slug' => 'mada', 'name' => ['ar' => 'شركة مدى للصرافة', 'en' => 'Mada Exchange'], 'url' => 'https://novasign-sy.com/Photos/mada.svg'],
            ['slug' => 'mcdodo', 'name' => ['ar' => 'مكدودو', 'en' => 'McDoDo'], 'url' => 'https://novasign-sy.com/Photos/mcdodo.svg'],
            ['slug' => 'morad', 'name' => ['ar' => 'حلويات مراد', 'en' => 'Morad Sweets'], 'url' => 'https://novasign-sy.com/Photos/morad.svg'],
            ['slug' => 'sham-pearl', 'name' => ['ar' => 'شام بيرل', 'en' => 'Sham Pearl'], 'url' => 'https://novasign-sy.com/Photos/sham%20pearl.svg'],
            ['slug' => 'sharqatli', 'name' => ['ar' => 'شرقطلي', 'en' => 'Sharqatli'], 'url' => 'https://novasign-sy.com/Photos/shar.svg'],
            ['slug' => 'zaitouna', 'name' => ['ar' => 'حلويات زيتونة', 'en' => 'Zaitouna Sweets'], 'url' => 'https://novasign-sy.com/Photos/zaitouna.svg'],
        ];

        foreach ($items as $index => $item) {
            $this->seedMediaRecord(
                repository: app(PartnerRepositoryContract::class),
                service: app(PartnerService::class),
                lookup: ['slug', $item['slug']],
                data: [
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
                url: $item['url'],
                collection: 'logo',
            );
        }
    }

    private function seedWhyUs(): void
    {
        $points = [
            ['slug' => 'innovation', 'body' => ['ar' => 'ابتكار مستمر و مواكبة أحدث التطورات الفنية و التقنية', 'en' => 'Continuous innovation that keeps pace with the latest artistic and technical developments.']],
            ['slug' => 'tailored-ideas', 'body' => ['ar' => 'أفكار إعلانية تناسب خصوصية كل علامة تجارية', 'en' => 'Advertising ideas tailored to the character of each brand.']],
            ['slug' => 'fast-delivery', 'body' => ['ar' => 'إنجاز سريع بحرفية عالية', 'en' => 'Fast delivery with a high level of craftsmanship.']],
        ];

        $repository = app(WhyUsPointRepositoryContract::class);
        $service = app(WhyUsPointService::class);

        foreach ($points as $index => $point) {
            if ($repository->findBy('slug', $point['slug'])) {
                continue;
            }

            $service->create([
                'body' => $point['body'],
                'slug' => $point['slug'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedServices(): void
    {
        $items = [
            [
                'slug' => 'advertising-signs',
                'title' => ['ar' => 'اللوحات الإعلانية', 'en' => 'Advertising Signs'],
                'description' => ['ar' => 'تصميم و تصنيع جميع أنواع اللوحات التجارية مع توفير خيارات واسعة من المواد', 'en' => 'Design and manufacture of all types of commercial signs, with a wide choice of materials.'],
                'url' => 'https://novasign-sy.com/Photos/Sign.png',
            ],
            [
                'slug' => 'advertising-sculptures',
                'title' => ['ar' => 'مجسمات إعلانية', 'en' => 'Advertising Sculptures'],
                'description' => ['ar' => 'تصميم و تنفيذ الستاندات و المجسمات الإعلانية مع اقتراح الخدمات المناسبة لكل تصميم', 'en' => 'Design and production of stands and advertising sculptures, with the right services suggested for each design.'],
                'url' => 'https://novasign-sy.com/Photos/Advertising-Sculptures.png',
            ],
            [
                'slug' => 'advertising-prints',
                'title' => ['ar' => 'المطبوعات الإعلانية', 'en' => 'Advertising Prints'],
                'description' => ['ar' => 'تنفيذ كافة المطبوعات الإعلانية بأجود الخامات العالمية و بأحدث التقنيات', 'en' => 'All advertising print work, using premium materials and the latest techniques.'],
                'url' => 'https://novasign-sy.com/Photos/Prints-Services.png',
            ],
            [
                'slug' => 'vehicle-wraps',
                'title' => ['ar' => 'لواصق سيارات', 'en' => 'Vehicle Wraps'],
                'description' => ['ar' => 'تركيب لواصق الفينيل الدعائية المقاومة للعوامل الجوية و بدقة طباعة عالية', 'en' => 'Weather-resistant vinyl wraps, installed with high print accuracy.'],
                'url' => 'https://novasign-sy.com/Photos/vinyl.png',
            ],
            [
                'slug' => 'decor-execution',
                'title' => ['ar' => 'تصميم و تنفيذ الديكور', 'en' => 'Décor Design and Execution'],
                'description' => ['ar' => 'تنفيذ تصاميم جميع الأنشطة التجارية مثل الصالات التجارية و أجنحة المعارض و المتاجر و غيرها', 'en' => 'Fit-outs for commercial spaces such as showrooms, exhibition booths, stores, and more.'],
                'url' => 'https://novasign-sy.com/Photos/Advertising-Decorations.png',
            ],
            [
                'slug' => 'led-screens',
                'title' => ['ar' => 'الشاشات الإعلانية', 'en' => 'Advertising Screens'],
                'description' => ['ar' => 'تركيب الشاشات الإعلانية الداخلية و الخارجية مع توفير خدمات ما بعد البيع و خدمات الصيانة', 'en' => 'Indoor and outdoor advertising screens, with after-sales support and maintenance.'],
                'url' => 'https://novasign-sy.com/Photos/LED-Screen.png',
            ],
        ];

        foreach ($items as $index => $item) {
            $this->seedMediaRecord(
                repository: app(ServiceItemRepositoryContract::class),
                service: app(ServiceItemService::class),
                lookup: ['slug', $item['slug']],
                data: [
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'slug' => $item['slug'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
                url: $item['url'],
                collection: 'image',
            );
        }
    }

    private function seedWorks(): void
    {
        $items = [
            ['slug' => 'installation-crew', 'title' => ['ar' => 'تركيب اللوحات', 'en' => 'Sign installation'], 'url' => 'https://novasign-sy.com/Photos/nova%20sign%20worker.webp'],
            ['slug' => 'modallalah', 'title' => ['ar' => 'مدللة', 'en' => 'Modallalah'], 'url' => 'https://novasign-sy.com/Photos/modallalah.webp'],
            ['slug' => 'center-sarouji', 'title' => ['ar' => 'سنتر سروجي', 'en' => 'Center Sarouji'], 'url' => 'https://novasign-sy.com/Photos/%D8%B3%D9%86%D8%AA%D8%B1%20%D8%B3%D8%B1%D9%88%D8%AC%D9%8A.webp'],
            ['slug' => 'sarouji', 'title' => ['ar' => 'سروجي', 'en' => 'Sarouji'], 'url' => 'https://novasign-sy.com/Photos/%D8%B3%D8%B1%D9%88%D8%AC%D9%8A.webp'],
            ['slug' => 'sham-pearl-sign', 'title' => ['ar' => 'شام بيرل', 'en' => 'Sham Pearl'], 'url' => 'https://novasign-sy.com/Photos/%D8%B4%D8%A7%D9%85%20%D8%A8%D9%8A%D8%B1%D9%84.webp'],
            ['slug' => 'sham-pearl-store', 'title' => ['ar' => 'شام بيرل', 'en' => 'Sham Pearl storefront'], 'url' => 'https://novasign-sy.com/Photos/Sham%20Pearl.webp'],
            ['slug' => 'al-saada-jewelry', 'title' => ['ar' => 'مجوهرات السعادة', 'en' => 'Al Saada Jewelry'], 'url' => 'https://novasign-sy.com/Photos/%D9%85%D8%AC%D9%88%D9%87%D8%B1%D8%A7%D8%AA%20%D8%A7%D9%84%D8%B3%D8%B9%D8%A7%D8%AF%D8%A9%20Alsaada%20Jewelry.webp'],
            ['slug' => 'al-saada-sign', 'title' => ['ar' => 'مجوهرات السعادة', 'en' => 'Al Saada sign'], 'url' => 'https://novasign-sy.com/Photos/%D9%85%D8%AC%D9%88%D9%87%D8%B1%D8%A7%D8%AA%20%D8%A7%D9%84%D8%B3%D8%B9%D8%A7%D8%AF%D8%A9.webp'],
            ['slug' => 'misk-clinics', 'title' => ['ar' => 'عيادات المسك', 'en' => 'Misk Clinics'], 'url' => 'https://novasign-sy.com/Photos/%D8%B9%D9%8A%D8%A7%D8%AF%D8%A7%D8%AA%20%D8%A7%D9%84%D9%85%D8%B3%D9%83.webp'],
            ['slug' => 'misk-sign', 'title' => ['ar' => 'عيادات المسك', 'en' => 'Misk Clinics sign'], 'url' => 'https://novasign-sy.com/Photos/Misk.webp'],
            ['slug' => 'stop-laptop', 'title' => ['ar' => 'ستوب لابتوب', 'en' => 'Stop Laptop'], 'url' => 'https://novasign-sy.com/Photos/Stop%20laptop.webp'],
            ['slug' => 'dr-reem', 'title' => ['ar' => 'الدكتورة ريم مصطفى السليم', 'en' => 'Dr. Reem Mustafa Al-Saleem'], 'url' => 'https://novasign-sy.com/Photos/DR.Reem.webp'],
        ];

        foreach ($items as $index => $item) {
            $this->seedMediaRecord(
                repository: app(PortfolioWorkRepositoryContract::class),
                service: app(PortfolioWorkService::class),
                lookup: ['slug', $item['slug']],
                data: [
                    'title' => $item['title'],
                    'slug' => $item['slug'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
                url: $item['url'],
                collection: 'image',
            );
        }
    }

    /**
     * @param  array{0: string, 1: string}  $lookup
     * @param  array<string, mixed>  $data
     */
    private function seedMediaRecord(
        object $repository,
        object $service,
        array $lookup,
        array $data,
        string $url,
        string $collection,
    ): void {
        $record = $repository->findBy($lookup[0], $lookup[1]);

        if (! $record instanceof Model) {
            $record = $service->create($data);
        }

        if ($record->getMedia($collection)->isNotEmpty()) {
            return;
        }

        try {
            $service->attachRemote($record, $url);
        } catch (Throwable) {
            // Remote assets are optional. The record stays editable in Filament.
        }
    }
}
