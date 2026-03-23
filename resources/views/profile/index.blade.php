@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="mb-8">
    <h2 class="text-2xl font-semibold text-slate-900">My Profile</h2>
    <p class="text-sm text-slate-500 mt-1">
        Informasi akun dan ringkasan aktivitas pengguna sistem ERP
    </p>
</div>

{{-- ================= PROFILE OVERVIEW ================= --}}
<div class="erp-card p-8 mb-8 flex flex-col md:flex-row md:items-center gap-8">

    {{-- AVATAR --}}
    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#f5e6b3] to-[#e0c97a]
                flex items-center justify-center
                text-3xl font-bold text-[#7a5c12] shadow-inner">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>

    {{-- BASIC INFO --}}
    <div class="flex-1">
        <h3 class="text-xl font-semibold text-slate-900">
            {{ $user->name }}
        </h3>
        <p class="text-sm text-slate-500">
            {{ $user->email }}
        </p>

        <div class="flex flex-wrap gap-3 mt-4">
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                         bg-[#f5e6b3] text-[#7a5c12]">
                {{ ucwords(str_replace('_',' ', $user->role->nama_role ?? 'User')) }}
            </span>

            <span class="px-4 py-1.5 rounded-full text-xs font-semibold
                         bg-green-100 text-green-700">
                Active
            </span>
        </div>
    </div>
</div>

{{-- ================= PROFILE GRID ================= --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

    {{-- ACCOUNT INFORMATION --}}
    <div class="erp-card p-6">
        <h4 class="font-semibold text-slate-900 mb-6">
            Account Information
        </h4>

        <ul class="space-y-4 text-sm">
            <li class="flex justify-between">
                <span class="text-slate-500">Role</span>
                <span class="font-medium text-slate-900">
                    {{ ucwords(str_replace('_',' ', $user->role->nama_role ?? '-')) }}
                </span>
            </li>

            <li class="flex justify-between">
                <span class="text-slate-500">Division</span>
                <span class="font-medium text-slate-900">
                    {{ $user->division->name ?? '-' }}
                </span>
            </li>

            <li class="flex justify-between">
                <span class="text-slate-500">Registered At</span>
                <span class="font-medium text-slate-900">
                    {{ $user->created_at->format('d M Y') }}
                </span>
            </li>
        </ul>
    </div>

    {{-- ACTIVITY SUMMARY --}}
    <div class="erp-card p-6">
        <h4 class="font-semibold text-slate-900 mb-6">
            Activity Summary
        </h4>

        <div class="grid grid-cols-2 gap-4 text-center">
            <div class="p-5 rounded-xl border border-slate-200">
                <p class="text-xs text-slate-500">
                    Total Activities
                </p>
                <p class="text-3xl font-bold text-[#d4af37] mt-2">
                    {{ $totalActivities }}
                </p>
            </div>

            <div class="p-5 rounded-xl border border-slate-200">
                <p class="text-xs text-slate-500">
                    This Month
                </p>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $monthlyActivities }}
                </p>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="erp-card p-6">
        <h4 class="font-semibold text-slate-900 mb-6">
            Quick Actions
        </h4>

        <div class="space-y-4">

            <a href="{{ route('profile.index') }}"
               class="block p-4 rounded-xl border border-slate-200
                      hover:border-[#d4af37] hover:bg-[#fffdf5]
                      transition">
                <p class="font-medium text-slate-900">
                    Refresh Profile
                </p>
                <p class="text-xs text-slate-500">
                    Perbarui tampilan data profil
                </p>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full text-left p-4 rounded-xl border border-slate-200
                           hover:border-red-300 hover:bg-red-50 transition">
                    <p class="font-medium text-red-600">
                        Logout
                    </p>
                    <p class="text-xs text-slate-500">
                        Keluar dari sistem ERP
                    </p>
                </button>
            </form>

        </div>
    </div>

</div>

@endsection
