@extends('layouts.karyawan')
@section('title', 'My Dashboard')

@section('content')

{{-- ================= WELCOME BANNER ================= --}}
<section class="mb-10 p-8 rounded-2xl bg-gradient-to-r from-slate-900 to-[#1a1c23] border border-slate-800 text-white shadow-xl relative overflow-hidden">
    <div class="relative z-10">
        <h2 class="text-3xl font-bold mb-2">Halo, {{ $user->name }}! 👋</h2>
        <p class="text-slate-300 max-w-2xl">
            Selamat datang di Employee Portal PT. Golden Intan Berlian. Pantau aktivitas harian, lihat laporan perusahaan, dan kelola informasi profil Anda melalui dashboard ini.
        </p>
        <div class="flex flex-wrap gap-3 mt-5">
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-[#f5e6b3]/20 text-[#f5e6b3] border border-[#d4af37]/30">
                {{ ucwords(str_replace('_', ' ', $user->role->nama_role ?? 'Karyawan')) }}
            </span>
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30">
                {{ $user->division->nama_divisi ?? 'Belum Ada Divisi' }}
            </span>
        </div>
    </div>

    <!-- Decorative Element -->
    <div class="absolute -right-10 -bottom-10 opacity-10">
        <svg width="220" height="220" viewBox="0 0 24 24" fill="none" stroke="#d4af37" stroke-width="0.5">
            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
        </svg>
    </div>
</section>

{{-- ================= PERSONAL SNAPSHOT ================= --}}
<section class="space-y-4 mb-10">
    <div>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Personal Snapshot</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan aktivitas dan performa Anda</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Total Aktivitas</p>
            <h3 class="text-3xl font-bold mt-2 text-slate-900 dark:text-white">{{ $totalActivities }}</h3>
            <p class="text-xs text-slate-500">Seluruh catatan Anda</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Bulan Ini</p>
            <h3 class="text-3xl font-bold mt-2 text-[#d4af37]">{{ $monthlyActivities }}</h3>
            <p class="text-xs text-slate-500">{{ now()->format('F Y') }}</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Minggu Ini</p>
            <h3 class="text-3xl font-bold mt-2 text-blue-600">{{ $weeklyActivities }}</h3>
            <p class="text-xs text-slate-500">Progres mingguan</p>
        </div>

        <div class="erp-card p-5">
            <p class="text-xs uppercase text-slate-400">Hari Ini</p>
            <h3 class="text-3xl font-bold mt-2 text-green-600">{{ $todayActivities }}</h3>
            <p class="text-xs text-slate-500">{{ now()->format('d M Y') }}</p>
        </div>
    </div>
</section>

{{-- ================= ACTIVITY CHART ================= --}}
<section class="mb-10 space-y-4">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Tren Aktivitas Saya</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Grafik aktivitas Anda selama 6 bulan terakhir</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
            <span class="w-3 h-3 rounded-full bg-[#d4af37]"></span>
            <span>Aktivitas</span>
        </div>
    </div>

    <div class="erp-card p-6">
        @if($hasChartData ?? false)
            <canvas id="myActivityChart" height="80"></canvas>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl mb-3">📊</div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Belum ada aktivitas</p>
                <p class="text-xs text-slate-400 mt-1">Grafik akan muncul setelah Anda mencatat aktivitas</p>
            </div>
        @endif
    </div>
</section>

{{-- ================= RECENT ACTIVITIES ================= --}}
<section class="space-y-4">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Aktivitas Terbaru</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">5 riwayat terakhir dari aktivitas Anda</p>
        </div>
        <a href="{{ route('karyawan.activities') }}"
           class="text-sm font-medium text-[#d4af37] hover:text-[#b8941f] transition">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="erp-card overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-6 py-4 font-medium text-slate-900 dark:text-white">Tanggal & Waktu</th>
                    <th class="px-6 py-4 font-medium text-slate-900 dark:text-white">Deskripsi Aktivitas</th>
                    <th class="px-6 py-4 font-medium text-slate-900 dark:text-white">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($recentActivities as $activity)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($activity->tanggal)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">{{ $activity->deskripsi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = match($activity->status) {
                                    'submitted' => 'blue',
                                    'created' => 'green',
                                    default => 'gray',
                                };
                            @endphp
                            <span class="px-2.5 py-1 text-xs rounded-full
                                bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700
                                dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-400">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="font-medium">Belum ada aktivitas tercatat</p>
                                <p class="text-xs mt-1">Aktivitas Anda akan muncul di sini setelah tercatat oleh sistem</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- ================= QUICK ACTIONS ================= --}}
<section class="mt-10 space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Quick Actions</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Akses cepat ke fitur utama</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('karyawan.activities') }}"
           class="erp-card p-6 hover:border-[#d4af37] hover:shadow-lg transition group">
            <div class="text-2xl mb-3">📝</div>
            <h4 class="font-semibold text-lg text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Aktivitas Saya</h4>
            <p class="text-sm text-slate-500 mt-1">Lihat riwayat lengkap aktivitas</p>
        </a>

        <a href="{{ route('karyawan.reports') }}"
           class="erp-card p-6 hover:border-[#d4af37] hover:shadow-lg transition group">
            <div class="text-2xl mb-3">📑</div>
            <h4 class="font-semibold text-lg text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Laporan Perusahaan</h4>
            <p class="text-sm text-slate-500 mt-1">Akses laporan yang dipublikasikan</p>
        </a>

        <a href="{{ route('karyawan.dashboard') }}"
           class="erp-card p-6 hover:border-[#d4af37] hover:shadow-lg transition group">
            <div class="text-2xl mb-3">📊</div>
            <h4 class="font-semibold text-lg text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Overview</h4>
            <p class="text-sm text-slate-500 mt-1">Lihat ringkasan performa Anda</p>
        </a>
    </div>
</section>

{{-- ================= CHART SCRIPT ================= --}}
@push('scripts')
@include('partials.chart-vite')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('myActivityChart');
    const hasChartData = @json($hasChartData ?? false);
    if (!ctx || !hasChartData) return;

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148,163,184,0.1)' : 'rgba(0,0,0,0.06)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Aktivitas Saya',
                data: @json($chartData),
                backgroundColor: isDark ? 'rgba(212,175,55,0.2)' : 'rgba(212,175,55,0.3)',
                borderColor: '#d4af37',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(212,175,55,0.5)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1e293b' : '#fff',
                    titleColor: isDark ? '#e2e8f0' : '#0f172a',
                    bodyColor: isDark ? '#94a3b8' : '#64748b',
                    borderColor: isDark ? '#334155' : '#e2e8f0',
                    borderWidth: 1,
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} aktivitas`
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Bulan', color: textColor },
                    grid: { display: false },
                    ticks: { color: textColor }
                },
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah', color: textColor },
                    ticks: { precision: 0, color: textColor },
                    grid: { color: gridColor }
                }
            }
        }
    });
});
</script>
@endpush

@endsection
