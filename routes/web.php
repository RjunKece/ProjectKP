<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT BY ROLE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();

    if (!$user || !$user->role) {
        abort(403, 'Role tidak ditemukan.');
    }

    return $user->role->nama_role === 'super_admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('karyawan.dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/profile', [ProfileController::class, 'index'])
    ->name('profile.index');

Route::middleware('auth')->put('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');

Route::middleware('auth')->post('/profile/password', [ProfileController::class, 'changePassword'])
    ->name('profile.password');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

// ===== USERS =====
Route::get('/users', [UserController::class, 'index'])
    ->name('users');

Route::post('/users', [UserController::class, 'store'])
    ->name('users.store');

Route::put('/users/{user}/reset-password',
    [UserController::class, 'resetPassword']
)->name('users.reset');

Route::put('/users/{user}',
    [UserController::class, 'update']
)->name('users.update');

        // ===== ACTIVITIES =====
        Route::get('/activities', [ActivityController::class, 'index'])
            ->name('activities');

        // ===== REPORTS =====
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');

        Route::post('/reports/store', [ReportController::class, 'store'])
            ->name('reports.store');

        Route::get('/reports/archive', [ReportController::class, 'archive'])
            ->name('reports.archive');

        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->name('reports.show');

        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])
            ->name('reports.destroy');
    });

/*
|--------------------------------------------------------------------------
| KARYAWAN ROUTES
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Karyawan\KaryawanDashboardController;
use App\Http\Controllers\Karyawan\KaryawanActivityController;
use App\Http\Controllers\Karyawan\KaryawanReportController;

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])
            ->name('dashboard');

        // ===== ACTIVITIES =====
        Route::get('/activities', [KaryawanActivityController::class, 'index'])
            ->name('activities');

        Route::post('/activities', [KaryawanActivityController::class, 'store'])
            ->name('activities.store');

        Route::put('/activities/{activity}', [KaryawanActivityController::class, 'updateStatus'])
            ->name('activities.update');

        // ===== REPORTS =====
        Route::get('/reports', [KaryawanReportController::class, 'index'])
            ->name('reports');

        Route::post('/reports', [KaryawanReportController::class, 'store'])
            ->name('reports.store');

        Route::get('/reports/{report}', [KaryawanReportController::class, 'show'])
            ->name('reports.show');

        Route::post('/reports/{report}/reply', [KaryawanReportController::class, 'reply'])
            ->name('reports.reply');
    });

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')->with('message', 'Anda telah berhasil logout. Silahkan login kembali untuk melanjutkan.');
})->name('logout');
