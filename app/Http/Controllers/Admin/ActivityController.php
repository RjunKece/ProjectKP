<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Role;
use App\Models\Division;
use App\Support\ActivityAnalytics;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()
            ->select(['id', 'user_id', 'tanggal', 'deskripsi', 'status'])
            ->with(['user:id,name,email,role_id,division_id', 'user.role:id,nama_role', 'user.division:id,nama_divisi'])
            ->orderByDesc('tanggal');

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

        if ($request->filled('role_id')) {
            $query->whereHas('user', fn ($q) => $q->where('role_id', $request->role_id));
        }

        if ($request->filled('division_id')) {
            $query->whereHas('user', fn ($q) => $q->where('division_id', $request->division_id));
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $activities = $query->paginate(15)->withQueryString();

        return view('admin.activities.index', array_merge(
            ActivityAnalytics::monitoringSummary(),
            [
                'activities' => $activities,
                'roles'      => Role::orderBy('id')->get(['id', 'nama_role']),
                'divisions'  => Division::orderBy('nama_divisi')->get(['id', 'nama_divisi']),
            ]
        ));
    }
}
