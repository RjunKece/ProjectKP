<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $deskripsi, string $status = 'success')
    {
        if (!Auth::check()) return;

        Activity::create([
            'user_id'   => Auth::id(),
            'tanggal'   => now(),
            'deskripsi' => $deskripsi,
            'status'    => $status,
        ]);
    }
}
