@extends('layouts.admin')
@section('title', 'Monitoring Aktivitas')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-10">
    <h2 class="text-2xl font-semibold text-slate-900">
        Monitoring Aktivitas
    </h2>
    <p class="text-sm text-slate-500 mt-1">
        Riwayat dan pemantauan aktivitas pengguna dalam sistem ERP
    </p>
</div>

{{-- ================= KPI SUMMARY ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wide text-slate-400">
            Total Aktivitas
        </p>
        <h3 class="text-3xl font-bold mt-2 text-slate-900">
            {{ $totalActivities ?? 0 }}
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Seluruh log tercatat
        </p>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wide text-slate-400">
            Aktivitas Hari Ini
        </p>
        <h3 class="text-3xl font-bold mt-2 text-[#d4af37]">
            {{ $todayActivities ?? 0 }}
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Update terbaru
        </p>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wide text-slate-400">
            Aktivitas Admin
        </p>
        <h3 class="text-3xl font-bold mt-2 text-blue-600">
            {{ $adminActivities ?? 0 }}
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Peran administratif
        </p>
    </div>

    <div class="erp-card p-6">
        <p class="text-xs uppercase tracking-wide text-slate-400">
            Aktivitas Karyawan
        </p>
        <h3 class="text-3xl font-bold mt-2 text-green-600">
            {{ $employeeActivities ?? 0 }}
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Operasional harian
        </p>
    </div>

</div>

{{-- ================= FILTER BAR ================= --}}
<div class="erp-card p-5 mb-8">
    <div class="flex flex-wrap gap-4 items-center">

        <input
            type="text"
            placeholder="Cari user atau aktivitas..."
            class="input w-64"
        >

        <select class="input w-44">
            <option>Semua Role</option>
            <option>Super Admin</option>
            <option>Admin</option>
            <option>Karyawan</option>
        </select>

        <select class="input w-44">
            <option>Semua Divisi</option>
            <option>Marketing</option>
            <option>Sales</option>
            <option>Keuangan</option>
        </select>

        <input type="date" class="input w-44">

    </div>
</div>

{{-- ================= ACTIVITY TABLE ================= --}}
<div class="erp-card overflow-hidden">

    <table class="w-full text-sm">
        <thead class="bg-[#fafafa] border-b border-slate-200 text-slate-500">
            <tr>
                <th class="px-6 py-3 text-left">User</th>
                <th class="px-6 py-3 text-left">Role</th>
                <th class="px-6 py-3 text-left">Divisi</th>
                <th class="px-6 py-3 text-left">Aktivitas</th>
                <th class="px-6 py-3 text-left">Waktu</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">

            {{-- EMPTY STATE --}}
            @if(empty($activities) || count($activities) === 0)
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <p class="text-slate-500 text-sm font-medium">
                            Belum ada aktivitas tercatat
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            Aktivitas akan muncul setelah sistem mulai digunakan
                        </p>
                    </td>
                </tr>
            @endif

            {{-- CONTOH DATA (SIAP KALO MAU DIPAKAI) --}}
            {{--
            <tr class="hover:bg-slate-50 transition">
                <td class="px-6 py-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#f5e6b3]
                                flex items-center justify-center
                                text-xs font-semibold text-[#8a6d1d]">
                        SA
                    </div>
                    <div>
                        <p class="font-medium text-slate-900">Super Admin</p>
                        <p class="text-xs text-slate-500">superadmin@erp.test</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-[#d4af37] font-medium">
                    Super Admin
                </td>
                <td class="px-6 py-4 text-slate-500">
                    -
                </td>
                <td class="px-6 py-4 text-slate-700">
                    Menambahkan data pengguna baru
                </td>
                <td class="px-6 py-4 text-slate-500">
                    23 Jan 2026 • 10:45
                </td>
            </tr>
            --}}
        </tbody>
    </table>

</div>

@endsection
