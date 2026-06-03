<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOperationalData extends Command
{
    protected $signature = 'erp:clean-operational-data
                            {--force : Skip confirmation prompt}
                            {--truncate : TRUNCATE (PostgreSQL, jauh lebih cepat dari DELETE)}';

    protected $description = 'Hapus aktivitas, laporan, dan log operasional (pertahankan users, roles, divisions)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm(
            'Ini akan menghapus SEMUA activities, reports, dan report_responses. Users/roles/divisions TIDAK dihapus. Lanjutkan?'
        )) {
            $this->info('Dibatalkan.');

            return self::SUCCESS;
        }

        $useTruncate = $this->option('truncate')
            || DB::connection()->getDriverName() === 'pgsql';

        DB::transaction(function () use ($useTruncate) {
            if ($useTruncate && DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('TRUNCATE TABLE report_responses, reports, activities RESTART IDENTITY CASCADE');
                $this->info('PostgreSQL TRUNCATE: report_responses, reports, activities — selesai.');
            } else {
                $responses  = DB::table('report_responses')->delete();
                $reports    = DB::table('reports')->delete();
                $activities = DB::table('activities')->delete();

                $this->info("report_responses dihapus: {$responses}");
                $this->info("reports dihapus: {$reports}");
                $this->info("activities dihapus: {$activities}");
            }
        });

        $this->newLine();
        $this->info('Data operasional berhasil dikosongkan. Dashboard & monitoring akan load jauh lebih cepat.');

        return self::SUCCESS;
    }
}
