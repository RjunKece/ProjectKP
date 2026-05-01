@extends('layouts.admin')
@section('title', 'User Management')

@section('content')

{{-- ================= NOTIFICATIONS ================= --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
         class="mb-6 px-5 py-3.5 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 px-5 py-3.5 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-300 text-sm">
        <ul class="list-disc pl-4 space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

{{-- ================= PAGE HEADER ================= --}}
<div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">User Management</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Kelola akun pengguna, role, dan divisi dalam sistem ERP
        </p>
    </div>
    <button onclick="openModal('addUserModal')"
            class="mt-4 md:mt-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                   bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                   hover:shadow-lg hover:shadow-[#d4af37]/20 hover:-translate-y-0.5 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Tambah User
    </button>
</div>

{{-- ================= KPI CARDS ================= --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="erp-card p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $totalUsers }}</h3>
        <p class="text-[11px] text-slate-500 mt-1">Total Pengguna Sistem</p>
    </div>

    <div class="erp-card p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-green-600">{{ $activeUsers }}</h3>
        <p class="text-[11px] text-slate-500 mt-1">Pengguna Aktif</p>
    </div>

    <div class="erp-card p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-[#d4af37]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-[#d4af37]">{{ $adminAccounts }}</h3>
        <p class="text-[11px] text-slate-500 mt-1">Super Admin</p>
    </div>

    <div class="erp-card p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-indigo-600">{{ $users->where('role.nama_role', 'karyawan')->count() }}</h3>
        <p class="text-[11px] text-slate-500 mt-1">Karyawan Ditampilkan</p>
    </div>
</div>

{{-- ================= FILTER BAR ================= --}}
<form method="GET" class="erp-card p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Cari</label>
            <input name="search" value="{{ request('search') }}" class="input w-full" placeholder="Cari nama atau email...">
        </div>
        <div class="w-40">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Role</label>
            <select name="role_id" class="input w-full">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_',' ', $role->nama_role)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Divisi</label>
            <select name="division_id" class="input w-full">
                <option value="">Semua Divisi</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->nama_divisi }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
            <a href="{{ route('admin.users') }}" class="btn-secondary px-4 py-2 text-sm">Reset</a>
        </div>
    </div>
</form>

{{-- ================= USER TABLE ================= --}}
<div class="erp-card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-700">
                <th class="px-6 py-4 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">User</th>
                <th class="px-6 py-4 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">Role</th>
                <th class="px-6 py-4 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">Divisi</th>
                <th class="px-6 py-4 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Status</th>
                <th class="px-6 py-4 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Aktivitas</th>
                <th class="px-6 py-4 text-right text-[10px] uppercase tracking-wider font-semibold text-slate-400">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse ($users as $user)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition group">
                {{-- USER --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @php $isAdmin = optional($user->role)->nama_role === 'super_admin'; @endphp
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-bold shrink-0
                                    {{ $isAdmin ? 'bg-gradient-to-br from-[#f5e6b3] to-[#d4af37] text-slate-900' : 'bg-gradient-to-br from-blue-400 to-blue-600 text-white' }}
                                    shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                {{-- ROLE --}}
                <td class="px-6 py-4">
                    @if($isAdmin)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-[#d4af37]/10 text-[#92710a] dark:text-[#f5e6b3] border border-[#d4af37]/15 dark:border-[#d4af37]/20">
                            ⭐ Super Admin
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                            👤 Karyawan
                        </span>
                    @endif
                </td>
                {{-- DIVISION --}}
                <td class="px-6 py-4">
                    <span class="text-slate-600 dark:text-slate-400 text-sm">
                        {{ $user->division->nama_divisi ?? '—' }}
                    </span>
                </td>
                {{-- STATUS --}}
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Active
                    </span>
                </td>
                {{-- ACTIVITY COUNT --}}
                <td class="px-6 py-4 text-center">
                    @php $actCount = \App\Models\Activity::where('user_id', $user->id)->count(); @endphp
                    <span class="font-bold text-slate-900 dark:text-white">{{ $actCount }}</span>
                    <span class="text-[10px] text-slate-400 block">log</span>
                </td>
                {{-- ACTION --}}
                <td class="px-6 py-4 text-right">
                    <button onclick="openResetModal({{ $user->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                                   bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300
                                   border border-amber-100 dark:border-amber-800
                                   hover:bg-amber-100 dark:hover:bg-amber-900/40 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Reset Password
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">📭</div>
                        <p class="text-slate-500 font-medium">Tidak ada data user ditemukan</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ================= MODALS ================= --}}
@include('admin.users.modals.add')
@include('admin.users.modals.reset')

{{-- ================= JS ================= --}}
<script>
function openModal(id){
    document.getElementById(id)?.classList.remove('hidden')
}
function closeModal(id){
    document.getElementById(id)?.classList.add('hidden')
}
function openResetModal(userId){
    const form = document.getElementById('resetForm');
    form.action = '{{ url("admin/users") }}/' + userId + '/reset-password';
    openModal('resetModal')
}
</script>

@endsection