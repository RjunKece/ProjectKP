<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@erp.test',
            'password' => Hash::make('password'),
            'role_id' => 1,      // super_admin
            'division_id' => null
        ]);
    }
}
