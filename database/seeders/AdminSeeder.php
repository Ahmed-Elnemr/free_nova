<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Role|null $superAdminRole */
        $superAdminRole = Role::query()
            ->where('guard_name', 'admin')
            ->where('name->en', 'Super Admin')
            ->first();

        $admin = Admin::query()->firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'phone' => '+966500500500',
                'avatar' => null,
                'password' => Hash::make('Test@123'),
                'is_active' => true,
            ]
        );

        if (! $superAdminRole) {
            $superAdminRole = Role::query()->create([
                'name' => [
                    'en' => 'Super Admin',
                    'ar' => 'السوبر ادمن',
                ],
                'guard_name' => 'admin',
            ]);
        }

        $permissionIds = Permission::query()
            ->where('guard_name', 'admin')
            ->pluck('id')
            ->all();

        $superAdminRole->syncPermissions($permissionIds);
        $admin->syncRoles([$superAdminRole]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
