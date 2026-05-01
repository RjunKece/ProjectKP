<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\DailyTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // KPIs
        $totalActivities = Activity::where('user_id', $user->id)->count();
        $todayActivities = Activity::where('user_id', $user->id)
            ->whereDate('tanggal', today())->count();
        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();
        $weeklyActivities = Activity::where('user_id', $user->id)
            ->whereBetween('tanggal', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();

        // Division info
        $divisionName = optional($user->division)->nama_divisi ?? '';
        $divisionId   = $user->division_id;

        // Daily targets for this division
        $dailyTargets = [];
        if ($divisionId) {
            $dailyTargets = DailyTarget::where('division_id', $divisionId)
                ->where('is_active', true)
                ->get()
                ->map(function ($target) use ($user) {
                    // Count how many times this target was done today
                    $doneToday = Activity::where('user_id', $user->id)
                        ->where('target_id', $target->id)
                        ->whereDate('tanggal', today())
                        ->count();

                    $target->done_today = $doneToday;
                    $target->progress   = $target->target_count > 0
                        ? min(100, round(($doneToday / $target->target_count) * 100))
                        : 0;

                    return $target;
                });
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
