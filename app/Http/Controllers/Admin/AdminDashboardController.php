<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
{
    // KPI
    $totalUsers = User::count();
    $activeEmployees = User::whereHas('role', fn ($q) =>
        $q->where('nama_role', 'karyawan')
    )->count();

    $totalActivities = Activity::count();
    $todayActivities = Activity::whereDate('tanggal', today())->count();

    $monthlyProductivity = rand(60, 90);
    $monthlyGrowth = rand(5, 20);

    // ===== CHART DATA =====
    $months = collect(range(0, 11))->map(fn ($i) =>
        Carbon::now()->subMonths(11 - $i)->format('M')
    );

    $karyawanData = [];
    $adminData = [];

    foreach (range(0, 11) as $i) {
        $date = Carbon::now()->subMonths(11 - $i);

        $karyawanData[] = Activity::whereMonth('tanggal', $date->month)
            ->whereYear('tanggal', $date->year)
            ->whereHas('user.role', fn ($q) =>
                $q->where('nama_role', 'karyawan')
            )->count();

        $adminData[] = Activity::whereMonth('tanggal', $date->month)
            ->whereYear('tanggal', $date->year)
            ->whereHas('user.role', fn ($q) =>
                $q->where('nama_role', 'super_admin')
            )->count();
    }

    $chartLabels = $months;

    $chartDatasets = [
        [
            'label' => 'Karyawan',
            'data' => $karyawanData,
            'borderColor' => '#2563eb',
            'backgroundColor' => 'rgba(37,99,235,0.15)',
            'tension' => 0.45,
        ],
        [
            'label' => 'Super Admin',
            'data' => $adminData,
            'borderColor' => '#d4af37',
            'backgroundColor' => 'rgba(212,175,55,0.25)',
            'tension' => 0.45,
        ]
    ];

    return view('admin.dashboard', compact(
        'totalUsers',
        'activeEmployees',
        'totalActivities',
        'todayActivities',
        'monthlyProductivity',
        'monthlyGrowth',
        'chartLabels',
        'chartDatasets'
    ));
}

}
