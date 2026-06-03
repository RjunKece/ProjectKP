<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role:id,nama_role', 'division:id,nama_divisi']);

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

        $users = $query->latest('id')->paginate(15)->withQueryString();

        $stats = $this->userStats();

        return view('admin.users.index', array_merge($stats, [
            'users'     => $users,
            'roles'     => Role::orderBy('id')->get(),
            'divisions' => Division::orderBy('nama_divisi')->get(),
        ]));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'role_id'     => 'required|exists:roles,id',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        if ($role->nama_role !== 'super_admin' && empty($validated['division_id'])) {
            return back()
                ->withErrors(['division_id' => 'Divisi wajib dipilih untuk karyawan.'])
                ->withInput();
        }

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'role_id'     => $validated['role_id'],
            'division_id' => $role->nama_role === 'super_admin' ? null : $validated['division_id'],
            'password'    => 'password123',
        ]);

        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Menambahkan user baru: ' . $user->name,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('admin.users', $request->only(['search', 'role_id', 'division_id']))
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'role_id'     => 'required|exists:roles,id',
            'division_id' => 'nullable|exists:divisions,id',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        if ($role->nama_role !== 'super_admin' && empty($validated['division_id'])) {
            return back()
                ->withErrors(['division_id' => 'Divisi wajib dipilih untuk karyawan.'])
                ->withInput();
        }

        $oldRole = optional($user->role)->nama_role;

        $user->update([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'role_id'     => $validated['role_id'],
            'division_id' => $role->nama_role === 'super_admin' ? null : $validated['division_id'],
        ]);

        $newRole = $role->nama_role;
        $description = 'Mengupdate data user: ' . $user->name;

        if ($oldRole !== $newRole) {
            $description .= ' (Role diubah: ' . str_replace('_', ' ', $oldRole) . ' → ' . str_replace('_', ' ', $newRole) . ')';
        }

        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => $description,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('admin.users')
            ->with('success', 'Data user berhasil diperbarui!');
    }

    public function resetPassword(User $user)
    {
        $user->update([
            'password' => 'password123',
        ]);

        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Mereset password user: ' . $user->name,
            'status'    => 'completed',
        ]);

        return back()->with('success', 'Password user berhasil di-reset ke default!');
    }

    /**
     * @return array{totalUsers: int, activeUsers: int, adminAccounts: int, employeeCount: int}
     */
    private function userStats(): array
    {
        $roleCounts = User::query()
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->selectRaw('roles.nama_role, COUNT(*) as total')
            ->groupBy('roles.id', 'roles.nama_role')
            ->pluck('total', 'nama_role');

        $karyawan = (int) ($roleCounts['karyawan'] ?? 0);
        $admins   = (int) ($roleCounts['super_admin'] ?? 0) + (int) ($roleCounts['admin'] ?? 0);

        return [
            'totalUsers'    => User::count(),
            'activeUsers'   => $karyawan,
            'adminAccounts' => $admins,
            'employeeCount' => $karyawan,
        ];
    }
}
