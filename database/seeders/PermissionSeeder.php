<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',

            'profile.view',
            'profile.password',

            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            'staff.view',
            'staff.create',
            'staff.edit',
            'staff.delete',
            'customers.view',
            'customers.show',
            'slots.view',
            'slots.create',
            'slots.edit',
            'slots.delete',

            'withdrawals.view',
            'withdrawals.approve',
            'withdrawals.reject',
            'recharges.view',
            'recharges.approve',
            'recharges.reject',
            'reports.winningsslots',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $adminUser = User::orderBy('id')->first();
        if ($adminUser) {
            $adminUser->assignRole($superAdmin);
        }
    }
}
