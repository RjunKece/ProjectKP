<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Division;
use App\Support\ActivityAnalytics;
use App\Support\DatabaseDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now       = now();
        $lastMonth = $now->copy()->subMonth();

        $userRow = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->selectRaw('COUNT(*) as total_users')
            ->selectRaw("SUM(CASE WHEN roles.nama_role = 'karyawan' THEN 1 ELSE 0 END) as active_employees")
            ->first();

        $reportRow = DB::table('reports')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'generated' THEN 1 ELSE 0 END) as pending")
            ->first();

        $activityRow = ActivityAnalytics::dashboardActivityStats($now, $lastMonth);

        $totalUsers      = (int) ($userRow->total_users ?? 0);
        $activeEmployees = (int) ($userRow->active_employees ?? 0);
        $totalActivities = (int) $activityRow->total;
        $todayActivities = (int) $activityRow->today;
        $thisMonth       = (int) $activityRow->this_month;
        $lastMonthCount  = (int) $activityRow->last_month;
        $totalReports    = (int) ($reportRow->total ?? 0);
        $pendingReports  = (int) ($reportRow->pending ?? 0);

        $monthlyProductivity = $totalActivities > 0
            ? min(round(($thisMonth / max($totalActivities, 1)) * 100), 100)
            : 0;
        $monthlyGrowth = $lastMonthCount > 0
            ? round((($thisMonth - $lastMonthCount) / $lastMonthCount) * 100)
            : ($thisMonth > 0 ? 100 : 0);

        $chartLabels = [];
        $chartDatasets = [];
        $hasChartData = false;
        $recentActivities = collect();
        $activityByDivision = collect();

        if ($totalActivities > 0 && $totalActivities <= ActivityAnalytics::HEAVY_ANALYTICS_LIMIT) {
            [$chartLabels, $chartDatasets, $hasChartData] = $this->buildActivityChart();
            $recentActivities = Activity::with('user:id,name')
                ->latest('tanggal')
                ->limit(6)
                ->get(['id', 'user_id', 'tanggal', 'deskripsi', 'status']);
            $activityByDivision = ActivityAnalytics::monthlyCountByDivision($now);
        } elseif ($totalActivities > ActivityAnalytics::HEAVY_ANALYTICS_LIMIT) {
            $recentActivities = collect();
        }

        $divisionUserCounts = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.nama_role', 'karyawan')
            ->whereNotNull('users.division_id')
            ->select('users.division_id', DB::raw('COUNT(*) as users_count'))
            ->groupBy('users.division_id')
            ->pluck('users_count', 'division_id');

        $divisions = Division::orderBy('nama_divisi')->get(['id', 'nama_divisi']);

        $divisionLabels = $divisions->pluck('nama_divisi')->toArray();
        $divisionData   = $divisions->map(fn ($d) => (int) ($divisionUserCounts[$d->id] ?? 0))->toArray();
        $divisionColors = ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'];
        $hasDivisionChartData = array_sum($divisionData) > 0;

        $divisionPerformance = $divisions->map(function ($div) use ($activityByDivision, $divisionUserCounts) {
            $usersCount = (int) ($divisionUserCounts[$div->id] ?? 0);
            $actCount   = (int) ($activityByDivision[$div->id] ?? 0);
            $div->users_count         = $usersCount;
            $div->monthly_activities  = $actCount;
            $div->avg_per_user = $usersCount > 0
                ? round($actCount / $usersCount, 1)
                : 0;

            return $div;
        })->sortByDesc('monthly_activities')->values();

        return view('admin.dashboard', compact(
            'totalUsers', 'activeEmployees', 'totalActivities', 'todayActivities',
            'totalReports', 'pendingReports',
            'monthlyProductivity', 'monthlyGrowth',
            'chartLabels', 'chartDatasets', 'hasChartData',
            'divisionLabels', 'divisionData', 'divisionColors',
            'hasDivisionChartData',
            'recentActivities', 'divisionPerformance'
        ));
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, array>, 2: bool}
     */
    private function buildActivityChart(): array
    {
        $chartLabels  = [];
        $karyawanData = [];
        $adminData    = [];
        $chartStart   = Carbon::now()->subMonths(11)->startOfMonth();

        foreach (range(0, 11) as $i) {
            $date = Carbon::now()->subMonths(11 - $i);
            $chartLabels[] = $date->format('M Y');
            $key = $date->format('Y-m');
            $karyawanData[$key] = 0;
            $adminData[$key]    = 0;
        }

        $monthExpr = DatabaseDate::monthKeyExpression('activities.tanggal');

        $rows = DB::table('activities')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('activities.tanggal', '>=', $chartStart)
            ->selectRaw("{$monthExpr} as month_key, roles.nama_role, COUNT(*) as total")
            ->groupBy(DB::raw($monthExpr), 'roles.nama_role')
            ->get();

        foreach ($rows as $row) {
            if (! isset($karyawanData[$row->month_key])) {
                continue;
            }
            if ($row->nama_role === 'karyawan') {
                $karyawanData[$row->month_key] = (int) $row->total;
            } elseif (in_array($row->nama_role, ['super_admin', 'admin'], true)) {
                $adminData[$row->month_key] += (int) $row->total;
            }
        }

        $karyawanSeries = array_values($karyawanData);
        $adminSeries    = array_values($adminData);
        $hasChartData   = array_sum($karyawanSeries) + array_sum($adminSeries) > 0;

        $chartDatasets = [
            [
                'label'                => 'Karyawan',
                'data'                 => $karyawanSeries,
                'borderColor'          => '#3b82f6',
                'backgroundColor'      => 'rgba(59,130,246,0.08)',
                'fill'                 => true,
                'tension'              => 0.4,
                'pointBackgroundColor' => '#3b82f6',
                'pointBorderColor'     => '#fff',
                'pointBorderWidth'     => 2,
            ],
            [
                'label'                => 'Super Admin',
                'data'                 => $adminSeries,
                'borderColor'          => '#d4af37',
                'backgroundColor'      => 'rgba(212,175,55,0.08)',
                'fill'                 => true,
                'tension'              => 0.4,
                'pointBackgroundColor' => '#d4af37',
                'pointBorderColor'     => '#fff',
                'pointBorderWidth'     => 2,
            ],
        ];

        return [$chartLabels, $chartDatasets, $hasChartData];
    }
}
