<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\DailyTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KaryawanActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user->load(['role', 'division']);

        $query = Activity::where('user_id', $user->id);

        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activities = $query->with('target')->orderBy('tanggal', 'desc')->paginate(15);

        // KPIs — single combined query instead of 4 separate COUNT queries
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd   = Carbon::now()->endOfWeek();
        $now       = now();

        $kpis = DB::table('activities')
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal <= ? THEN 1 ELSE 0 END) as today_count', [
                $now->copy()->startOfDay(), $now->copy()->endOfDay()
            ])
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 ELSE 0 END) as monthly_count', [
                $now->copy()->startOfMonth(), $now->copy()->startOfMonth()->addMonth()
            ])
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal <= ? THEN 1 ELSE 0 END) as weekly_count', [
                $weekStart, $weekEnd
            ])
            ->first();

        $totalActivities   = (int) ($kpis->total ?? 0);
        $todayActivities   = (int) ($kpis->today_count ?? 0);
        $monthlyActivities = (int) ($kpis->monthly_count ?? 0);
        $weeklyActivities  = (int) ($kpis->weekly_count ?? 0);

        // Division info
        $divisionName = optional($user->division)->nama_divisi ?? '';
        $divisionId   = $user->division_id;

        // Daily targets for this division — batch query to avoid N+1
        $dailyTargets = [];
        if ($divisionId) {
            $dailyTargets = DailyTarget::where('division_id', $divisionId)
                ->where('is_active', true)
                ->get();

            if ($dailyTargets->isNotEmpty()) {
                // Single query: get today's done count per target_id
                $targetDoneCounts = DB::table('activities')
                    ->where('user_id', $user->id)
                    ->whereIn('target_id', $dailyTargets->pluck('id'))
                    ->whereBetween('tanggal', [today()->startOfDay(), today()->endOfDay()])
                    ->select('target_id', DB::raw('COUNT(*) as done'))
                    ->groupBy('target_id')
                    ->pluck('done', 'target_id');

                $dailyTargets = $dailyTargets->map(function ($target) use ($targetDoneCounts) {
                    $doneToday = (int) ($targetDoneCounts[$target->id] ?? 0);
                    $target->done_today = $doneToday;
                    $target->progress   = $target->target_count > 0
                        ? min(100, round(($doneToday / $target->target_count) * 100))
                        : 0;

                    return $target;
                });
            }
        }

        return view('karyawan.activities.index', compact(
            'activities',
            'totalActivities',
            'todayActivities',
            'monthlyActivities',
            'weeklyActivities',
            'divisionName',
            'dailyTargets'
        ));
    }

    /**
     * Store a new activity with optional file/link
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi'  => 'required|string|max:1000',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:submitted,in_progress,completed',
            'target_id'  => 'nullable|exists:daily_targets,id',
            'link'       => 'nullable|url|max:500',
            'file'       => 'nullable|file|max:10240', // max 10MB
        ]);

        $data = [
            'user_id'   => Auth::id(),
            'deskripsi' => $validated['deskripsi'],
            'tanggal'   => $validated['tanggal'],
            'status'    => $validated['status'],
            'target_id' => $validated['target_id'] ?? null,
            'link'      => $validated['link'] ?? null,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('activities', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
        }

        Activity::create($data);

        return redirect()
            ->route('karyawan.activities')
            ->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    /**
     * Update activity (status, deskripsi, file, link)
     */
    public function updateStatus(Request $request, Activity $activity)
    {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status'    => 'required|in:submitted,in_progress,completed',
            'deskripsi' => 'nullable|string|max:1000',
            'link'      => 'nullable|url|max:500',
            'file'      => 'nullable|file|max:10240',
        ]);

        $data = ['status' => $validated['status']];

        if ($request->filled('deskripsi')) {
            $data['deskripsi'] = $validated['deskripsi'];
        }

        if ($request->filled('link')) {
            $data['link'] = $validated['link'];
        }

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($activity->file_path) {
                Storage::disk('public')->delete($activity->file_path);
            }
            $file = $request->file('file');
            $data['file_path'] = $file->store('activities', 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $activity->update($data);

        return redirect()
            ->route('karyawan.activities')
            ->with('success', 'Aktivitas berhasil diperbarui!');
    }
}
