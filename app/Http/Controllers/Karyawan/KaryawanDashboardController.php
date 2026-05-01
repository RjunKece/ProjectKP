<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Personal KPIs
        $totalActivities = Activity::where('user_id', $user->id)->count();

        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $todayActivities = Activity::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->count();

        $weeklyActivities = Activity::where('user_id', $user->id)
            ->whereBetween('tanggal', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();

        // Recent activities (5 latest)
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Chart data - last 6 months personal activity
        $chartLabels = [];
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M');
            $chartData[] = Activity::where('user_id', $user->id)
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)
                ->count();
        }

        return view('karyawan.dashboard', compact(
            'user',
            'totalActivities',
            'monthlyActivities',
            'todayActivities',
            'weeklyActivities',
            'recentActivities',
            'chartLabels',
            'chartData'
        ));
    }
}
