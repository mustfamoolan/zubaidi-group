<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // صلاحيات المستخدمين
            [
                'name' => 'users.view',
                'display_name' => 'عرض المستخدمين',
                'description' => 'إمكانية عرض قائمة المستخدمين',
                'group' => 'users'
            ],
            [
                'name' => 'users.create',
                'display_name' => 'إضافة مستخدم',
                'description' => 'إمكانية إضافة مستخدم جديد',
                'group' => 'users'
            ],
            [
                'name' => 'users.edit',
                'display_name' => 'تعديل مستخدم',
                'description' => 'إمكانية تعديل بيانات المستخدمين',
                'group' => 'users'
            ],
            [
                'name' => 'users.delete',
                'display_name' => 'حذف مستخدم',
                'description' => 'إمكانية حذف المستخدمين',
                'group' => 'users'
            ],

            // صلاحيات الأدوار
            [
                'name' => 'roles.view',
                'display_name' => 'عرض الأدوار',
                'description' => 'إمكانية عرض قائمة الأدوار',
                'group' => 'roles'
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'إضافة دور',
                'description' => 'إمكانية إضافة دور جديد',
                'group' => 'roles'
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'تعديل دور',
                'description' => 'إمكانية تعديل الأدوار',
                'group' => 'roles'
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'حذف دور',
                'description' => 'إمكانية حذف الأدوار',
                'group' => 'roles'
            ],

            // صلاحيات الصلاحيات
            [
                'name' => 'permissions.view',
                'display_name' => 'عرض الصلاحيات',
                'description' => 'إمكانية عرض قائمة الصلاحيات',
                'group' => 'permissions'
            ],
            [
                'name' => 'permissions.edit',
                'display_name' => 'تعديل الصلاحيات',
                'description' => 'إمكانية تعديل صلاحيات الأدوار',
                'group' => 'permissions'
            ],

            // صلاحيات عامة
            [
                'name' => 'dashboard.view',
                'display_name' => 'عرض لوحة التحكم',
                'description' => 'إمكانية الوصول للوحة التحكم',
                'group' => 'general'
            ],
            [
                'name' => 'reports.view',
                'display_name' => 'عرض التقارير',
                'description' => 'إمكانية عرض التقارير',
                'group' => 'reports'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
