<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed struktur dasar saja (roles, divisions, super admin, daily targets).
     * Data dummy aktivitas/laporan TIDAK di-seed — gunakan: php artisan erp:clean-operational-data --force
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DivisionSeeder::class,
            SuperAdminSeeder::class,
            DailyTargetSeeder::class,
        ]);
    }

}
