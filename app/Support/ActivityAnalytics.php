<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Query aktivitas yang dioptimasi untuk Supabase/PostgreSQL (hindari full-table scan).
 */
final class ActivityAnalytics
{
    /** Batas log — di atas ini heatmap/top performers tidak dihitung (hemat Render free tier). */
    public const HEAVY_ANALYTICS_LIMIT = 3000;

    public static function hasAny(): bool
    {
        return DB::table('activities')->limit(1)->exists();
    }

    public static function totalCount(): int
    {
        return self::resolveTotalCount();
    }

    /**
     * Hindari COUNT(*) penuh pada tabel raksasa — pakai estimasi PostgreSQL jika > limit.
     */
    public static function resolveTotalCount(): int
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            $row = DB::selectOne("
                SELECT COALESCE(c.reltuples, 0)::bigint AS estimate
                FROM pg_class c
                JOIN pg_namespace n ON n.oid = c.relnamespace
                WHERE c.relname = 'activities' AND n.nspname = 'public'
            ");
            $estimate = (int) ($row->estimate ?? 0);
            if ($estimate > self::HEAVY_ANALYTICS_LIMIT) {
                return $estimate;
            }
        }

        return (int) DB::table('activities')->count();
    }

    /**
     * KPI dashboard admin — query terpisah memakai index tanggal (lebih cepat dari 1x SUM besar).
     *
     * @return object{total: int, today: int, this_month: int, last_month: int}
     */
    public static function dashboardActivityStats(Carbon $now, Carbon $lastMonth): object
    {
        if (! self::hasAny()) {
            return (object) ['total' => 0, 'today' => 0, 'this_month' => 0, 'last_month' => 0];
        }

        $total = self::resolveTotalCount();
        if ($total > self::HEAVY_ANALYTICS_LIMIT) {
            return (object) ['total' => $total, 'today' => 0, 'this_month' => 0, 'last_month' => 0];
        }

        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd   = $thisMonthStart->copy()->addMonth();
        $lastMonthStart = $lastMonth->copy()->startOfMonth();
        $lastMonthEnd   = $lastMonthStart->copy()->addMonth();

        $today = (int) DB::table('activities')
            ->whereBetween('tanggal', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])
            ->count();

        $thisMonth = (int) DB::table('activities')
            ->where('tanggal', '>=', $thisMonthStart)
            ->where('tanggal', '<', $thisMonthEnd)
            ->count();

        $lastMonthCount = (int) DB::table('activities')
            ->where('tanggal', '>=', $lastMonthStart)
            ->where('tanggal', '<', $lastMonthEnd)
            ->count();

        return (object) [
            'total'      => $total,
            'today'      => $today,
            'this_month' => $thisMonth,
            'last_month' => $lastMonthCount,
        ];
    }

    /**
     * Ringkasan halaman monitoring — hanya scan window terbatas untuk chart.
     *
     * @return array<string, mixed>
     */
    public static function monitoringSummary(): array
    {
        $empty = [
            'totalActivities'    => 0,
            'todayActivities'    => 0,
            'weekActivities'     => 0,
            'adminActivities'    => 0,
            'employeeActivities' => 0,
            'statusCounts'       => [],
            'weeklyTrend'        => [],
            'topPerformers'      => collect(),
            'heatmap'            => [],
            'heatMax'            => 0,
            'heavyAnalytics'     => false,
        ];

        if (! self::hasAny()) {
            return $empty;
        }

        $total = self::totalCount();
        $heavy = $total > self::HEAVY_ANALYTICS_LIMIT;

        $today     = today();
        $weekStart = now()->subDays(6)->startOfDay();
        $dayExpr   = DatabaseDate::dayExpression('tanggal');

        $todayCount = (int) DB::table('activities')
            ->whereBetween('tanggal', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->count();

        $weekCount = (int) DB::table('activities')
            ->where('tanggal', '>=', $weekStart)
            ->count();

        $adminCount = 0;
        $employeeCount = 0;

        if (! $heavy) {
            $roleCounts = DB::table('activities')
                ->join('users', 'activities.user_id', '=', 'users.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->selectRaw("SUM(CASE WHEN roles.nama_role = 'super_admin' THEN 1 ELSE 0 END) as admin_count")
                ->selectRaw("SUM(CASE WHEN roles.nama_role = 'karyawan' THEN 1 ELSE 0 END) as employee_count")
                ->first();
            $adminCount    = (int) ($roleCounts->admin_count ?? 0);
            $employeeCount = (int) ($roleCounts->employee_count ?? 0);
        }

        $weeklyTrend = [];
        $statusCounts = [];
        $topPerformers = collect();
        $heatmap = [];
        $heatMax = 0;

        if (! $heavy) {
            $windowStart = now()->subDays(29)->startOfDay();

            $statusCounts = DB::table('activities')
                ->where('tanggal', '>=', $windowStart)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $dailyCounts = DB::table('activities')
                ->where('tanggal', '>=', $weekStart)
                ->selectRaw("{$dayExpr} as day, COUNT(*) as total")
                ->groupBy(DB::raw($dayExpr))
                ->pluck('total', 'day');

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $weeklyTrend[] = [
                    'day'   => Carbon::parse($date)->format('D'),
                    'date'  => $date,
                    'count' => (int) ($dailyCounts[$date] ?? 0),
                ];
            }

            $monthStart = now()->startOfMonth();
            $topRows = DB::table('activities')
                ->select('user_id', DB::raw('COUNT(*) as total'))
                ->where('tanggal', '>=', $monthStart)
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $userIds = $topRows->pluck('user_id')->filter()->unique();
            $users = $userIds->isEmpty()
                ? collect()
                : DB::table('users')
                    ->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                    ->whereIn('users.id', $userIds)
                    ->get(['users.id', 'users.name', 'divisions.nama_divisi']);

            $topPerformers = $topRows->map(function ($row) use ($users) {
                $u = $users->firstWhere('id', $row->user_id);
                $row->user = $u ? (object) [
                    'name'     => $u->name,
                    'division' => $u->nama_divisi ? (object) ['nama_divisi' => $u->nama_divisi] : null,
                ] : null;

                return $row;
            });

            $heatmapCounts = DB::table('activities')
                ->where('tanggal', '>=', $windowStart)
                ->selectRaw("{$dayExpr} as day, COUNT(*) as total")
                ->groupBy(DB::raw($dayExpr))
                ->pluck('total', 'day');

            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $count = (int) ($heatmapCounts[$date] ?? 0);
                $heatmap[] = [
                    'date'  => $date,
                    'count' => $count,
                    'label' => Carbon::parse($date)->format('d M'),
                ];
                if ($count > $heatMax) {
                    $heatMax = $count;
                }
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $weeklyTrend[] = [
                    'day'   => Carbon::parse($date)->format('D'),
                    'date'  => $date,
                    'count' => 0,
                ];
            }
        }

        return [
            'totalActivities'    => $total,
            'todayActivities'    => $todayCount,
            'weekActivities'     => $weekCount,
            'adminActivities'    => $adminCount,
            'employeeActivities' => $employeeCount,
            'statusCounts'       => $statusCounts,
            'weeklyTrend'        => $weeklyTrend,
            'topPerformers'      => $topPerformers,
            'heatmap'            => $heatmap,
            'heatMax'            => $heatMax,
            'heavyAnalytics'     => $heavy,
        ];
    }

    /**
     * Aktivitas per divisi (bulan berjalan) — 1 query, skip jika tidak ada data.
     *
     * @return Collection<int, int> division_id => total
     */
    public static function monthlyCountByDivision(Carbon $now): Collection
    {
        if (! self::hasAny()) {
            return collect();
        }

        $start = $now->copy()->startOfMonth();
        $end   = $start->copy()->addMonth();

        return DB::table('activities')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->whereNotNull('users.division_id')
            ->where('activities.tanggal', '>=', $start)
            ->where('activities.tanggal', '<', $end)
            ->select('users.division_id', DB::raw('COUNT(*) as total'))
            ->groupBy('users.division_id')
            ->pluck('total', 'division_id');
    }
}
