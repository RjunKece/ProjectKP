<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register', [
            'divisions' => Division::orderBy('nama_divisi')->get(),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'division_id' => ['required', 'exists:divisions,id'],
        ]);

        $karyawanRole = Role::where('nama_role', 'karyawan')->firstOrFail();

        User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => $validated['password'],
            'role_id'     => $karyawanRole->id,
            'division_id' => $validated['division_id'],
        ]);

        return redirect()
            ->route('login')
            ->with('message', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }
}
