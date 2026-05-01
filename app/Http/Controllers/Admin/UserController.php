<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'division']);

        // Hanya tampilkan admin dan karyawan yang aktif (contoh & contoh3)
        $query->where(function ($q) {
            $q->whereHas('role', fn($r) => $r->where('nama_role', 'super_admin'))
              ->orWhereIn('email', ['contoh@gmail.com', 'contoh3@gmail.com']);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $users = $query->latest()->get();

        // KPI Stats
        $totalUsers     = User::count();
        $activeUsers    = User::count(); // all are active in this prototype
        $adminAccounts  = User::whereHas('role', fn($q) => $q->where('nama_role', 'super_admin'))->count();
        $pendingApproval = 0;

        return view('admin.users.index', [
            'users'           => $users,
            'roles'           => Role::all(),
            'divisions'       => Division::all(),
            'totalUsers'      => $totalUsers,
            'activeUsers'     => $activeUsers,
            'adminAccounts'   => $adminAccounts,
            'pendingApproval' => $pendingApproval,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'role_id'     => 'required|exists:roles,id',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'role_id'     => $validated['role_id'],
            'division_id' => $validated['division_id'],
            'password'    => Hash::make('password123'),
        ]);

        // LOG AKTIVITAS
        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Menambahkan user baru: ' . $user->name,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password123'),
        ]);

        // LOG AKTIVITAS
        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Mereset password user: ' . $user->name,
            'status'    => 'completed',
        ]);

        return back()->with('success', 'Password user berhasil di-reset ke default!');
    }
}
