<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('homepage.brand_name_ar', null);
        $this->migrator->add('homepage.brand_name_en', null);
        $this->migrator->add('homepage.about_title_ar', null);
        $this->migrator->add('homepage.about_title_en', null);
        $this->migrator->add('homepage.about_body_ar', null);
        $this->migrator->add('homepage.about_body_en', null);
        $this->migrator->add('homepage.partners_title_ar', null);
        $this->migrator->add('homepage.partners_title_en', null);
        $this->migrator->add('homepage.why_title_ar', null);
        $this->migrator->add('homepage.why_title_en', null);
        $this->migrator->add('homepage.why_lead_ar', null);
        $this->migrator->add('homepage.why_lead_en', null);
        $this->migrator->add('homepage.services_title_ar', null);
        $this->migrator->add('homepage.services_title_en', null);
        $this->migrator->add('homepage.works_title_ar', null);
        $this->migrator->add('homepage.works_title_en', null);
        $this->migrator->add('homepage.contact_title_ar', null);
        $this->migrator->add('homepage.contact_title_en', null);
        $this->migrator->add('homepage.meta_title_ar', null);
        $this->migrator->add('homepage.meta_title_en', null);
        $this->migrator->add('homepage.meta_description_ar', null);
        $this->migrator->add('homepage.meta_description_en', null);
    }

    public function down(): void
    {
        $this->migrator->delete('homepage.brand_name_ar');
        $this->migrator->delete('homepage.brand_name_en');
        $this->migrator->delete('homepage.about_title_ar');
        $this->migrator->delete('homepage.about_title_en');
        $this->migrator->delete('homepage.about_body_ar');
        $this->migrator->delete('homepage.about_body_en');
        $this->migrator->delete('homepage.partners_title_ar');
        $this->migrator->delete('homepage.partners_title_en');
        $this->migrator->delete('homepage.why_title_ar');
        $this->migrator->delete('homepage.why_title_en');
        $this->migrator->delete('homepage.why_lead_ar');
        $this->migrator->delete('homepage.why_lead_en');
        $this->migrator->delete('homepage.services_title_ar');
        $this->migrator->delete('homepage.services_title_en');
        $this->migrator->delete('homepage.works_title_ar');
        $this->migrator->delete('homepage.works_title_en');
        $this->migrator->delete('homepage.contact_title_ar');
        $this->migrator->delete('homepage.contact_title_en');
        $this->migrator->delete('homepage.meta_title_ar');
        $this->migrator->delete('homepage.meta_title_en');
        $this->migrator->delete('homepage.meta_description_ar');
        $this->migrator->delete('homepage.meta_description_en');
    }
};
