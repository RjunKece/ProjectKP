<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        Activity::truncate();

        $deskripsi = [
            'Login ke sistem',
            'Input data transaksi',
            'Update data karyawan',
            'Generate laporan',
            'Logout dari sistem',
        ];

        // 12 bulan ke belakang
        for ($bulan = 0; $bulan < 12; $bulan++) {
            $tanggal = Carbon::now()->subMonths($bulan);

            // Karyawan (lebih banyak)
            for ($i = 0; $i < rand(5, 15); $i++) {
                Activity::create([
                    'user_id'   => rand(2, 5), // karyawan
                    'tanggal'   => $tanggal->copy()->addDays(rand(0, 20)),
                    'deskripsi' => $deskripsi[array_rand($deskripsi)],
                    'status'    => 'submitted',
                ]);
            }

            // Super Admin (lebih sedikit)
            for ($i = 0; $i < rand(2, 6); $i++) {
                Activity::create([
                    'user_id'   => 1, // super admin
                    'tanggal'   => $tanggal->copy()->addDays(rand(0, 20)),
                    'deskripsi' => 'Administrasi sistem',
                    'status'    => 'approved',
                ]);
            }
        }
    }
}
