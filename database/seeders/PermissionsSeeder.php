<?php

namespace Database\Seeders;

use App\Support\PermissionGenerator;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->generateAdminPermissions();
    }

    private function generateAdminPermissions(): void
    {
        $actions = ['read', 'create', 'update', 'delete'];

        // Full CRUD modules
        $fullCrudEntities = [
            'Admins',
            'Roles',
            'Users',
            'Blogs',
            'Notification_groups',
            'Contact_messages',
            'Partners',
            'Service_items',
            'Portfolio_works',
            'Why_us_points',
            'Site_media',
        ];

        PermissionGenerator::generate($fullCrudEntities, $actions, 'admin');

        // Settings: read + update only
        $settingsEntities = [
            'General_settings',
            'Contact_settings',
            'About_us_settings',
            'Homepage_settings',
            'Terms_settings',
            'Privacy_settings',
        ];

        PermissionGenerator::generate($settingsEntities, ['read', 'update'], 'admin');
    }
}
