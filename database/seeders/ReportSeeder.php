<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        Report::insert([
            [
                'title' => 'Laporan Keuangan Bulanan',
                'type' => 'financial',
                'description' => 'Analisis pemasukan dan pengeluaran perusahaan.',
                'status' => 'ready',
                'start_date' => Carbon::now()->subMonth(),
                'created_by' => 1,
            ],
            [
                'title' => 'Evaluasi Kinerja Divisi',
                'type' => 'department',
                'description' => 'Evaluasi performa dan efisiensi kerja tiap divisi.',
                'status' => 'ready',
                'start_date' => Carbon::now()->subWeeks(2),
                'created_by' => 1,
            ],
            [
                'title' => 'Analisis Pertumbuhan',
                'type' => 'growth',
                'description' => 'Analisis tren pertumbuhan perusahaan.',
                'status' => 'ready',
                'start_date' => Carbon::now(),
                'created_by' => 1,
            ],
        ]);
    }
}
