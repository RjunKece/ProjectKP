<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {

        $activities = Activity::with([
            'user.role',
            'user.division'
        ])
        ->orderBy('tanggal','desc')
        ->paginate(15);


        $totalActivities = Activity::count();


        $todayActivities = Activity::whereDate(
            'tanggal',
            today()
        )->count();


        $adminActivities = Activity::whereHas('user.role', function($q){
            $q->where('nama_role','super_admin');
        })->count();


        $employeeActivities = Activity::whereHas('user.role', function($q){
            $q->where('nama_role','karyawan');
        })->count();


        return view('admin.activities.index',[
            'activities' => $activities,
            'totalActivities' => $totalActivities,
            'todayActivities' => $todayActivities,
            'adminActivities' => $adminActivities,
            'employeeActivities' => $employeeActivities
        ]);

    }
}