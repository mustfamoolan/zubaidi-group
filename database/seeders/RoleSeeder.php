<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام له صلاحية كاملة'
            ]
        );

        $employeeRole = Role::firstOrCreate(
            ['name' => 'employee'],
            [
                'display_name' => 'موظف',
                'description' => 'موظف عادي بصلاحيات محدودة'
            ]
        );

        // إعطاء جميع الصلاحيات للمدير
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // إعطاء صلاحيات محدودة للموظف
        $employeePermissions = Permission::whereIn('name', [
            'dashboard.view',
            'reports.view',
        ])->get();

        $employeeRole->permissions()->sync($employeePermissions->pluck('id'));
    }
}
