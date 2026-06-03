<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed struktur dasar + demo data minimal untuk production.
     * Data dummy berat TIDAK di-seed.
     * Cleanup: php artisan erp:clean-operational-data --force --seed-demo
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DivisionSeeder::class,
            SuperAdminSeeder::class,
            DailyTargetSeeder::class,
            ProductionDemoSeeder::class,
        ]);
    }
}
