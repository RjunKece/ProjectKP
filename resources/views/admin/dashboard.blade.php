@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')

{{-- ================= SYSTEM SNAPSHOT ================= --}}
<section class="space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-slate-900">System Snapshot</h2>
        <p class="text-sm text-slate-500">
            Ringkasan performa dan kondisi sistem ERP saat ini
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-6">

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Total Users</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalUsers ?? 0 }}</h3>
            <p class="text-xs text-slate-500">Akun terdaftar</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Active Employees</p>
            <h3 class="text-3xl font-bold mt-2">{{ $activeEmployees ?? 0 }}</h3>
            <p class="text-xs text-green-600">Aktif bulan ini</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Activities</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalActivities ?? 0 }}</h3>
            <p class="text-xs text-slate-500">Total log aktivitas</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Today Activity</p>
            <h3 class="text-3xl font-bold mt-2">{{ $todayActivities ?? 0 }}</h3>
            <p class="text-xs text-blue-600">Hari ini</p>
        </div>

        <div class="erp-card p-5 border border-[#d4af37]">
            <p class="text-xs uppercase text-slate-400">Productivity</p>
            <h3 class="text-3xl font-bold mt-2 text-[#d4af37]">
                {{ $monthlyProductivity ?? 0 }}%
            </h3>
            <p class="text-xs text-slate-500">Estimasi bulanan</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Growth</p>
            <h3 class="text-3xl font-bold mt-2 text-green-600">
                +{{ $monthlyGrowth ?? 0 }}%
            </h3>
            <p class="text-xs text-slate-500">Dari bulan lalu</p>
        </div>

    </div>
</section>

{{-- ================= ACTIVITY ANALYTICS ================= --}}
<section class="mt-12 space-y-4">

    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Activity Analytics</h2>
            <p class="text-sm text-slate-500">
                Grafik aktivitas karyawan selama 6 bulan terakhir
            </p>
        </div>

        <div class="flex items-center gap-4 text-sm text-slate-600">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span>Karyawan</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#d4af37]"></span>
                <span>Super Admin</span>
            </div>
        </div>
    </div>

    <div class="erp-card p-6">
        <canvas id="activityChart" height="90"></canvas>
    </div>

</section>

{{-- ================= QUICK ACTIONS ================= --}}
<section class="mt-10 space-y-4">

    <div>
        <h2 class="text-xl font-semibold text-slate-900">Quick Actions</h2>
        <p class="text-sm text-slate-500">
            Akses cepat untuk administrasi utama
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.users') }}" class="erp-card p-6">
            <h3 class="font-semibold text-lg">Manage Users</h3>
            <p class="text-sm text-slate-500 mt-1">Kelola akun pengguna</p>
        </a>

        <a href="{{ route('admin.activities') }}" class="erp-card p-6">
            <h3 class="font-semibold text-lg">Review Activities</h3>
            <p class="text-sm text-slate-500 mt-1">Monitoring aktivitas</p>
        </a>

        <a href="{{ route('admin.reports') }}" class="erp-card p-6">
            <h3 class="font-semibold text-lg">Generate Reports</h3>
            <p class="text-sm text-slate-500 mt-1">Laporan sistem</p>
        </a>
    </div>

</section>

{{-- ================= CHART SCRIPT (SATU KALI, FINAL) ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('activityChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: @json($chartDatasets ?? [])
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} aktivitas`
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Periode Bulan' },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah Aktivitas' },
                    ticks: { precision: 0 }
                }
            },
            elements: {
                line: { tension: 0.45, borderWidth: 2 },
                point: { radius: 4, hoverRadius: 6 }
            }
        }
    });
});
</script>

@endsection
