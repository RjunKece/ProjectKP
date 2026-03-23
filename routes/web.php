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

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

// ===== USERS =====
Route::get('/users', [UserController::class, 'index'])
    ->name('users');

// 🔥 TAMBAHAN INI (WAJIB)
Route::post('/users', [UserController::class, 'store'])
    ->name('users.store');
Route::post('/users', [UserController::class, 'store'])
    ->name('admin.users.store');
Route::put('/users/{user}/reset-password',
    [UserController::class, 'resetPassword']
)->name('users.reset');

        // ===== ACTIVITIES =====
        Route::get('/activities', [ActivityController::class, 'index'])
            ->name('activities');

        // ===== REPORTS =====
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');

        Route::post('/reports/generate', [ReportController::class, 'generate'])
            ->name('reports.generate');

        Route::post('/reports/preview', [ReportController::class, 'preview'])
            ->name('reports.preview');

        Route::post('/reports/store', [ReportController::class, 'store'])
            ->name('reports.store');

        Route::get('/reports/archive', [ReportController::class, 'archive'])
            ->name('reports.archive');

        Route::get('/reports/{report}', [ReportController::class, 'show'])
            ->name('reports.show');
    });

/*
|--------------------------------------------------------------------------
| KARYAWAN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('karyawan.dashboard');
        })->name('dashboard');
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

    return redirect('/login');
})->name('logout');
