<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $saRole = Role::where('nama_role', 'super_admin')->firstOrFail();

        // Primary super admin (production)
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'        => 'Super Admin',
                'password'    => Hash::make('admin123'),
                'role_id'     => $saRole->id,
                'division_id' => null,
            ]
        );

        // Legacy super admin (dev/testing)
        User::firstOrCreate(
            ['email' => 'superadmin@erp.test'],
            [
                'name'        => 'Super Admin',
                'password'    => Hash::make('password'),
                'role_id'     => $saRole->id,
                'division_id' => null,
            ]
        );
    }
}
