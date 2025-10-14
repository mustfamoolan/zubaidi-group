<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // إنشاء مدير النظام الافتراضي
        User::firstOrCreate(
            ['email' => 'admin@alzubaidi.com'],
            [
                'name' => 'مدير النظام',
                'phone' => '1234567890',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // إنشاء موظف تجريبي
        User::firstOrCreate(
            ['email' => 'employee@alzubaidi.com'],
            [
                'name' => 'موظف تجريبي',
                'phone' => '0987654321',
                'password' => Hash::make('12345678'),
                'role_id' => $employeeRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
