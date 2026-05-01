<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:super_admin') or middleware('role:karyawan')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            abort(403, 'Akses ditolak. Role tidak ditemukan.');
        }

        $userRole = $user->role->nama_role;

        if (!in_array($userRole, $roles)) {
            // Redirect to their proper dashboard instead of 403
            if ($userRole === 'super_admin') {
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('karyawan.dashboard')
                ->with('warning', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
