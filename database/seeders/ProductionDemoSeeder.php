<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Division;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk PRODUCTION — data demo minimal.
 * - 1 karyawan per divisi (8 total)
 * - Beberapa aktivitas contoh
 * - 2 laporan contoh
 *
 * Jalankan: php artisan db:seed --class=ProductionDemoSeeder
 */
class ProductionDemoSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanRole = Role::where('nama_role', 'karyawan')->firstOrFail();
        $divisions = Division::orderBy('id')->get();

        if ($divisions->isEmpty()) {
            $this->command->warn('Divisions belum ada — jalankan DivisionSeeder terlebih dahulu.');
            return;
        }

        // ===== 1 KARYAWAN PER DIVISI =====
        $createdUsers = [];
        foreach ($divisions as $idx => $division) {
            $num = $idx + 1;
            $user = User::firstOrCreate(
                ['email' => "karyawan{$num}@erp.test"],
                [
                    'name'        => "Karyawan {$division->nama_divisi}",
                    'password'    => Hash::make('password'),
                    'role_id'     => $karyawanRole->id,
                    'division_id' => $division->id,
                ]
            );
            $createdUsers[] = $user;
        }

        $this->command->info('✅ ' . count($createdUsers) . ' karyawan demo dibuat (1 per divisi).');

        // ===== AKTIVITAS DEMO (3 per karyawan = ~24 total) =====
        $activityTemplates = [
            ['deskripsi' => 'Menyelesaikan tugas harian divisi', 'status' => 'completed'],
            ['deskripsi' => 'Meeting koordinasi tim', 'status' => 'completed'],
            ['deskripsi' => 'Menyiapkan laporan mingguan', 'status' => 'in_progress'],
        ];

        $activityCount = 0;
        foreach ($createdUsers as $user) {
            foreach ($activityTemplates as $i => $template) {
                Activity::firstOrCreate(
                    [
                        'user_id'   => $user->id,
                        'deskripsi' => $template['deskripsi'],
                        'tanggal'   => Carbon::now()->subDays($i),
                    ],
                    [
                        'status' => $template['status'],
                    ]
                );
                $activityCount++;
            }
        }

        $this->command->info("✅ {$activityCount} aktivitas demo dibuat.");

        // ===== LAPORAN DEMO (2 saja) =====
        $superAdmin = User::whereHas('role', fn($q) => $q->where('nama_role', 'super_admin'))->first();
        $creatorId = $superAdmin ? $superAdmin->id : 1;

        Report::firstOrCreate(
            ['title' => 'Laporan Kinerja Bulanan'],
            [
                'type'        => 'performance',
                'scope'       => 'company',
                'description' => 'Ringkasan kinerja seluruh divisi bulan ini.',
                'status'      => 'ready',
                'priority'    => 'normal',
                'start_date'  => Carbon::now()->startOfMonth(),
                'created_by'  => $creatorId,
            ]
        );

        Report::firstOrCreate(
            ['title' => 'Evaluasi Divisi Marketing'],
            [
                'type'        => 'department',
                'scope'       => 'division',
                'division_id' => Division::where('nama_divisi', 'Marketing')->value('id'),
                'description' => 'Evaluasi performa divisi marketing Q2.',
                'status'      => 'ready',
                'priority'    => 'normal',
                'start_date'  => Carbon::now()->subWeek(),
                'created_by'  => $creatorId,
            ]
        );

        $this->command->info('✅ 2 laporan demo dibuat.');
        $this->command->newLine();
        $this->command->info('🎉 Production demo data siap — dashboard & monitoring akan ringan.');
    }
}
