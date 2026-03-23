@extends('layouts.admin')
@section('title', 'User Management')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="mb-10">
    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
        User Management
    </h2>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
        Kelola akun pengguna, role, dan divisi dalam sistem ERP
    </p>
</div>


{{-- ================= SUMMARY ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Total Users
        </p>
        <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-2">
            {{ $totalUsers ?? $users->count() }}
        </h3>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Active Users
        </p>
        <h3 class="text-3xl font-bold text-green-600 mt-2">
            {{ $activeUsers ?? $users->count() }}
        </h3>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Super Admin
        </p>
        <h3 class="text-3xl font-bold text-[#d4af37] mt-2">
            {{ $adminAccounts ?? 0 }}
        </h3>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
            Pending Approval
        </p>
        <h3 class="text-3xl font-bold text-yellow-600 mt-2">
            {{ $pendingApproval ?? 0 }}
        </h3>
    </div>

</div>


{{-- ================= FILTER TOOLBAR ================= --}}
<form method="GET"
      class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

    <div class="flex flex-wrap gap-3">

        <input
            name="search"
            value="{{ request('search') }}"
            class="input w-64"
            placeholder="Search name or email..."
        >

        <select name="role_id" class="input w-44">
            <option value="">All Roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}"
                    {{ request('role_id') == $role->id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ', $role->nama_role)) }}
                </option>
            @endforeach
        </select>

        <select name="division_id" class="input w-44">
            <option value="">All Divisions</option>
            @foreach ($divisions as $division)
                <option value="{{ $division->id }}"
                    {{ request('division_id') == $division->id ? 'selected' : '' }}>
                    {{ $division->nama_divisi }}
                </option>
            @endforeach
        </select>

        <button class="btn-primary">
            Filter
        </button>

    </div>

    <button type="button"
            onclick="openModal('addUserModal')"
            class="btn-primary">
        + Add User
    </button>

</form>


{{-- ================= TABLE ================= --}}
<div class="erp-card overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400">
<tr>
<th class="px-6 py-4 text-left">User</th>
<th class="px-6 py-4 text-left">Role</th>
<th class="px-6 py-4 text-left">Division</th>
<th class="px-6 py-4 text-left">Status</th>
<th class="px-6 py-4 text-right">Action</th>
</tr>
</thead>

<tbody class="divide-y divide-slate-200 dark:divide-slate-700">

@forelse ($users as $user)

<tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">

<td class="px-6 py-4">

<div class="flex items-center gap-3">

<div class="w-9 h-9 rounded-full bg-[#f5e6b3]
flex items-center justify-center
font-semibold text-[#8a6d1d]">

{{ strtoupper(substr($user->name, 0, 1)) }}

</div>

<div>
<p class="font-medium text-slate-900 dark:text-white">
{{ $user->name }}
</p>

<p class="text-xs text-slate-500 dark:text-slate-400">
{{ $user->email }}
</p>
</div>

</div>

</td>


{{-- ROLE --}}
<td class="px-6 py-4">

@if(optional($user->role)->nama_role === 'super_admin')

<span class="badge badge-gold">Super Admin</span>

@elseif(optional($user->role)->nama_role === 'karyawan')

<span class="badge badge-blue">Karyawan</span>

@else

<span class="badge badge-gray">-</span>

@endif

</td>


{{-- DIVISION --}}
<td class="px-6 py-4 text-slate-500 dark:text-slate-400">

{{ $user->division->nama_divisi ?? '-' }}

</td>


{{-- STATUS --}}
<td class="px-6 py-4">

<span class="badge badge-green">
Active
</span>

</td>


{{-- ACTION --}}
<td class="px-6 py-4 text-right space-x-2">

<button
onclick="openResetModal({{ $user->id }})"
class="px-3 py-1.5 rounded-lg text-xs
bg-yellow-50 dark:bg-yellow-900/40
text-yellow-700 dark:text-yellow-300
hover:bg-yellow-100 dark:hover:bg-yellow-900/60">

Reset Password

</button>

</td>

</tr>

@empty

<tr>
<td colspan="5"
class="px-6 py-16 text-center text-slate-500 dark:text-slate-400">

Tidak ada data user ditemukan

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

const form = document.getElementById('resetForm')

form.action = `/admin/users/${userId}/reset-password`

openModal('resetModal')

}

</script>

@endsection