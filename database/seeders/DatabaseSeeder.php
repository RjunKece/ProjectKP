<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
 public function run(): void
{
    $this->call([
        RoleSeeder::class,
        DivisionSeeder::class,
        SuperAdminSeeder::class,
        UserSeeder::class,
        DailyTargetSeeder::class,
        ActivitySeeder::class,
        ReportSeeder::class,
    ]);
}

}
