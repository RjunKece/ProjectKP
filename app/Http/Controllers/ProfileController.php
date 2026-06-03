<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile — auto-detect layout by role
     */
    public function index()
    {
        $user = auth()->user();
        $user->load(['role', 'division']);

        // Single combined query instead of 2 separate counts
        $stats = \Illuminate\Support\Facades\DB::table('activities')
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN tanggal >= ? AND tanggal < ? THEN 1 ELSE 0 END) as monthly', [
                now()->startOfMonth(), now()->startOfMonth()->addMonth()
            ])
            ->first();

        $totalActivities   = (int) ($stats->total ?? 0);
        $monthlyActivities = (int) ($stats->monthly ?? 0);

        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get(['id', 'tanggal', 'deskripsi', 'status']);

        $isAdmin = optional($user->role)->nama_role === 'super_admin';

        $view = $isAdmin ? 'admin.profile.index' : 'karyawan.profile.index';

        return view($view, [
            'user'              => $user,
            'totalActivities'   => $totalActivities,
            'monthlyActivities' => $monthlyActivities,
            'recentActivities'  => $recentActivities,
        ]);
    }

    /**
     * Update profile info (name, email)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        Activity::create([
            'user_id'   => $user->id,
            'tanggal'   => now(),
            'deskripsi' => 'Memperbarui profil: ' . $user->name,
            'status'    => 'completed',
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        $user->update([
            'password' => $request->new_password,
        ]);

        Activity::create([
            'user_id'   => $user->id,
            'tanggal'   => now(),
            'deskripsi' => 'Mengubah password akun',
            'status'    => 'completed',
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
