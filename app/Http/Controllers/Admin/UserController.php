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
        $users = User::with(['role', 'division'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::all(),
            'divisions' => Division::all(),
        ]);
    }

    // ===============================
    // STORE USER (INI YANG TADI BELUM)
    // ===============================
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
            'password'    => Hash::make('password123'), // default demo
        ]);

        // LOG AKTIVITAS (PENTING)
        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Menambahkan user baru: ' . $user->name,
            'status'    => 'created',
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password123'),
        ]);

        return back()->with('success', 'Password user berhasil di-reset');
    }
}
