<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        Activity::truncate();

        $karyawanIds = User::whereHas('role', fn($q) => $q->where('nama_role', 'karyawan'))->pluck('id')->toArray();
        $adminId = User::whereHas('role', fn($q) => $q->where('nama_role', 'super_admin'))->value('id') ?? 1;

        $karyawanActions = [
            'Login ke sistem',
            'Input data transaksi harian',
            'Upload dokumen penjualan',
            'Update progress tugas',
            'Submit laporan mingguan',
            'Mengisi form kehadiran',
            'Review data pelanggan',
            'Membuat konten marketing',
            'Update stok gudang',
            'Input data CRM pelanggan baru',
            'Follow-up klien via email',
            'Membuat desain visual konten',
            'Upload video ke platform',
            'Edit laporan keuangan bulanan',
            'Menyelesaikan tugas divisi',
        ];

        $adminActions = [
            'Review laporan karyawan',
            'Approve permintaan divisi',
            'Generate laporan bulanan',
            'Update konfigurasi sistem',
            'Backup database',
            'Reset password karyawan',
            'Menambahkan user baru',
            'Monitoring aktivitas karyawan',
            'Administrasi sistem',
        ];

        $statuses = ['submitted', 'completed', 'approved'];

        // Generate 12 bulan data
        for ($bulan = 0; $bulan < 12; $bulan++) {
            $baseDate = Carbon::now()->subMonths($bulan)->startOfMonth();
            $daysInMonth = $baseDate->daysInMonth;

            // Karyawan activities (semakin recent semakin banyak)
            $karyawanCount = rand(8, 15) + max(0, 12 - $bulan);
            for ($i = 0; $i < $karyawanCount; $i++) {
                $userId = $karyawanIds[array_rand($karyawanIds)] ?? 2;
                $day = rand(1, min($daysInMonth, $bulan === 0 ? now()->day : $daysInMonth));

                Activity::create([
                    'user_id'   => $userId,
                    'tanggal'   => $baseDate->copy()->addDays($day - 1)->setHour(rand(8, 17))->setMinute(rand(0, 59)),
                    'deskripsi' => $karyawanActions[array_rand($karyawanActions)],
                    'status'    => $statuses[array_rand($statuses)],
                ]);
            }

            // Admin activities (lebih sedikit)
            $adminCount = rand(3, 7) + max(0, intdiv(12 - $bulan, 2));
            for ($i = 0; $i < $adminCount; $i++) {
                $day = rand(1, min($daysInMonth, $bulan === 0 ? now()->day : $daysInMonth));

                Activity::create([
                    'user_id'   => $adminId,
                    'tanggal'   => $baseDate->copy()->addDays($day - 1)->setHour(rand(9, 18))->setMinute(rand(0, 59)),
                    'deskripsi' => $adminActions[array_rand($adminActions)],
                    'status'    => 'completed',
                ]);
            }
        }
    }
}
