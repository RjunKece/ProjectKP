<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('divisions')->insert([
            ['nama_divisi' => 'Marketing'],
            ['nama_divisi' => 'Sales'],
            ['nama_divisi' => 'Keuangan'],
            ['nama_divisi' => 'Konten Kreator'],
            ['nama_divisi' => 'Gudang'],
            ['nama_divisi' => 'CRM'],
            ['nama_divisi' => 'YouTube'],
            ['nama_divisi' => 'Admin Marketplace'],
        ]);
    }
}
