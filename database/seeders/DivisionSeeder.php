<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Marketing',
            'Sales',
            'Keuangan',
            'Konten Kreator',
            'Gudang',
            'CRM',
            'YouTube',
            'Admin Marketplace',
        ];

        foreach ($divisions as $name) {
            Division::firstOrCreate(['nama_divisi' => $name]);
        }
    }
}
