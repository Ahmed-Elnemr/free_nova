<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Settings\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_endpoint_returns_arabic_and_english_content(): void
    {
        $settings = app(HomepageSetting::class);
        $settings->brand_name_ar = 'نوفا ساين';
        $settings->brand_name_en = 'Nova Sign';
        $settings->about_title_ar = 'من نحن';
        $settings->about_title_en = 'About';
        $settings->about_body_ar = 'نبذة';
        $settings->about_body_en = 'About body';
        $settings->partners_title_ar = 'شركاء النجاح';
        $settings->partners_title_en = 'Partners';
        $settings->why_title_ar = 'لماذا نحن';
        $settings->why_title_en = 'Why us';
        $settings->why_lead_ar = 'خبرة';
        $settings->why_lead_en = 'Experience';
        $settings->services_title_ar = 'خدماتنا';
        $settings->services_title_en = 'Services';
        $settings->works_title_ar = 'أعمالنا';
        $settings->works_title_en = 'Works';
        $settings->contact_title_ar = 'تواصل معنا';
        $settings->contact_title_en = 'Contact';
        $settings->meta_title_ar = 'نوفا';
        $settings->meta_title_en = 'Nova';
        $settings->meta_description_ar = 'وصف';
        $settings->meta_description_en = 'Description';
        $settings->save();

        Partner::query()->create([
            'name' => ['ar' => 'بيسكوزا', 'en' => 'Biscoza'],
            'slug' => 'biscoza',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $arabic = $this->withHeader('X-Locale', 'ar')->getJson('/api/v1/client/home');
        $arabic->assertOk()
            ->assertJsonPath('data.brand.name', 'نوفا ساين')
            ->assertJsonPath('data.partners.items.0.name', 'بيسكوزا');

        $english = $this->withHeader('X-Locale', 'en')->getJson('/api/v1/client/home');
        $english->assertOk()
            ->assertJsonPath('data.brand.name', 'Nova Sign')
            ->assertJsonPath('data.partners.items.0.name', 'Biscoza');
    }
}
