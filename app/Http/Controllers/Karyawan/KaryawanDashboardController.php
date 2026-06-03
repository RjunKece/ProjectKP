<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Support\DatabaseDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;

        $now       = now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd   = $monthStart->copy()->addMonth();
        $weekStart  = Carbon::now()->startOfWeek();
        $weekEnd    = Carbon::now()->endOfWeek();

        $stats = DB::table('activities')
            ->where('user_id', $userId)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 ELSE 0 END) as monthly', [$monthStart, $monthEnd])
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal <= ? THEN 1 ELSE 0 END) as weekly', [$weekStart, $weekEnd])
            ->first();

        if (DB::connection()->getDriverName() !== 'pgsql') {
            $stats = DB::table('activities')
                ->where('user_id', $userId)
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN MONTH(tanggal) = ? AND YEAR(tanggal) = ? THEN 1 ELSE 0 END) as monthly', [$now->month, $now->year])
                ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal <= ? THEN 1 ELSE 0 END) as weekly', [$weekStart, $weekEnd])
                ->first();
        }

        $totalActivities   = (int) ($stats->total ?? 0);
        $monthlyActivities = (int) ($stats->monthly ?? 0);
        $weeklyActivities  = (int) ($stats->weekly ?? 0);

        $todayActivities = $totalActivities > 0
            ? (int) DB::table('activities')
                ->where('user_id', $userId)
                ->whereBetween('tanggal', [$now->copy()->startOfDay(), $now->copy()->endOfDay()])
                ->count()
            : 0;

        $recentActivities = $totalActivities > 0
            ? Activity::query()
                ->where('user_id', $userId)
                ->select(['id', 'tanggal', 'deskripsi', 'status'])
                ->orderByDesc('tanggal')
                ->limit(5)
                ->get()
            : collect();

        $chartLabels = [];
        $chartData   = [];
        $monthKeys   = [];
        $hasChartData = false;

        if ($totalActivities > 0) {
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $chartLabels[] = $date->format('M');
                $monthKeys[] = $date->format('Y-m');
                $chartData[$date->format('Y-m')] = 0;
            }

            $monthExpr = DatabaseDate::monthKeyExpression('tanggal');
            $rows = DB::table('activities')
                ->where('user_id', $userId)
                ->where('tanggal', '>=', Carbon::now()->subMonths(5)->startOfMonth())
                ->selectRaw("{$monthExpr} as month_key, COUNT(*) as total")
                ->groupBy(DB::raw($monthExpr))
                ->pluck('total', 'month_key');

            foreach ($monthKeys as $key) {
                $chartData[$key] = (int) ($rows[$key] ?? 0);
            }

            $chartData = array_values($chartData);
            $hasChartData = array_sum($chartData) > 0;
        }

        return view('karyawan.dashboard', compact(
            'user',
            'totalActivities',
            'monthlyActivities',
            'todayActivities',
            'weeklyActivities',
            'recentActivities',
            'chartLabels',
            'chartData',
            'hasChartData'
        ));
    }
}
