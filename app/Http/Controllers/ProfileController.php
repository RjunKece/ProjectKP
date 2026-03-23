<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // hitung aktivitas (kalau belum ada tabel, hasilnya 0)
        $totalActivities = Activity::where('user_id', $user->id)->count();

        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('profile.index', [
            'user' => $user,
            'totalActivities' => $totalActivities,
            'monthlyActivities' => $monthlyActivities
        ]);
    }
}
