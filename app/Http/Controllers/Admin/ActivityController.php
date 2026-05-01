<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Role;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // ── Build filtered query ──
        $query = Activity::with(['user.role', 'user.division'])
            ->orderBy('tanggal', 'desc');

        // Search by user name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role_id', $request->role_id);
            });
        }

        // Filter by division
        if ($request->filled('division_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activities = $query->paginate(15)->appends($request->query());

        // ── KPI Summary ──
        $totalActivities    = Activity::count();
        $todayActivities    = Activity::whereDate('tanggal', today())->count();
        $weekActivities     = Activity::where('tanggal', '>=', now()->subDays(7))->count();

        $adminActivities = Activity::whereHas('user.role', function ($q) {
            $q->where('nama_role', 'super_admin');
        })->count();

        $employeeActivities = Activity::whereHas('user.role', function ($q) {
            $q->where('nama_role', 'karyawan');
        })->count();

        // ── Status breakdown ──
        $statusCounts = Activity::selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // ── Weekly trend (last 7 days) ──
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $weeklyTrend[] = [
                'day'   => Carbon::parse($date)->format('D'),
                'date'  => $date,
                'count' => Activity::whereDate('tanggal', $date)->count(),
            ];
        }

        // ── Dropdown data ──
        $roles     = Role::all();
        $divisions = Division::all();

        return view('admin.activities.index', [
            'activities'         => $activities,
            'totalActivities'    => $totalActivities,
            'todayActivities'    => $todayActivities,
            'weekActivities'     => $weekActivities,
            'adminActivities'    => $adminActivities,
            'employeeActivities' => $employeeActivities,
            'statusCounts'       => $statusCounts,
            'weeklyTrend'        => $weeklyTrend,
            'roles'              => $roles,
            'divisions'          => $divisions,
        ]);
    }
}