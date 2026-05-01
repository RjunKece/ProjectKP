@extends('layouts.admin')
@section('title', 'Dashboard Overview')

@section('content')

{{-- ================= WELCOME BANNER ================= --}}
<section class="mb-8">
    <div class="erp-card p-0 overflow-hidden">
        <div class="relative bg-gradient-to-r from-slate-900 via-[#1a1c2e] to-[#1e1b3a] px-8 py-7">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">
                        Selamat Datang, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-slate-400 text-sm max-w-xl">
                        Monitor performa sistem, kelola aktivitas karyawan, dan pantau laporan melalui dashboard ini.
                    </p>
                </div>
                <div class="hidden lg:flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl bg-white/5 backdrop-blur border border-white/10">
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Tanggal</p>
                        <p class="text-sm font-semibold text-white">{{ now()->format('d M Y') }}</p>
                    </div>
                    <div class="px-4 py-2 rounded-xl bg-[#d4af37]/10 backdrop-blur border border-[#d4af37]/20">
                        <p class="text-[10px] text-[#d4af37]/70 uppercase tracking-wider">Status</p>
                        <p class="text-sm font-semibold text-[#d4af37]">● Sistem Aktif</p>
                    </div>
                </div>
            </div>
            {{-- Decorative --}}
            <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-[#d4af37]/5 blur-2xl"></div>
            <div class="absolute -left-4 -bottom-4 w-32 h-32 rounded-full bg-blue-500/5 blur-2xl"></div>
        </div>
    </div>
</section>

{{-- ================= KPI STRIP ================= --}}
<section class="mb-8">
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">

        <div class="erp-card p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-lg">👥</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Total Users</p>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalUsers }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">Akun terdaftar</p>
        </div>

        <div class="erp-card p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-lg">🟢</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Karyawan Aktif</p>
            </div>
            <h3 class="text-3xl font-extrabold text-green-600">{{ $activeEmployees }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">Aktif saat ini</p>
        </div>

        <div class="erp-card p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-lg">📊</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Total Aktivitas</p>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalActivities }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">Seluruh log</p>
        </div>

        <div class="erp-card p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-lg">📝</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Hari Ini</p>
            </div>
            <h3 class="text-3xl font-extrabold text-blue-600">{{ $todayActivities }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">{{ now()->format('d M Y') }}</p>
        </div>

        <div class="erp-card p-5 border-[#d4af37]/30 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#d4af37]/10 flex items-center justify-center text-lg">⚡</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Produktivitas</p>
            </div>
            <h3 class="text-3xl font-extrabold text-[#d4af37]">{{ $monthlyProductivity }}%</h3>
            <p class="text-[11px] text-slate-500 mt-1">Estimasi bulan ini</p>
        </div>

        <div class="erp-card p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-lg">📈</div>
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Growth</p>
            </div>
            <h3 class="text-3xl font-extrabold {{ $monthlyGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ $monthlyGrowth >= 0 ? '+' : '' }}{{ $monthlyGrowth }}%
            </h3>
            <p class="text-[11px] text-slate-500 mt-1">vs bulan lalu</p>
        </div>

    </div>
</section>

{{-- ================= CHARTS ROW ================= --}}
<section class="mb-8 grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Activity Line Chart (2/3 width) --}}
    <div class="xl:col-span-2">
        <div class="erp-card p-6 h-full">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">📈 Activity Analytics</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Tren aktivitas 12 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Karyawan
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#d4af37]"></span> Admin
                    </div>
                </div>
            </div>
            <canvas id="activityChart" height="85"></canvas>
        </div>
    </div>

    {{-- Division Pie Chart (1/3 width) --}}
    <div>
        <div class="erp-card p-6 h-full">
            <div class="mb-6">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">🏢 Distribusi Divisi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Karyawan per divisi</p>
            </div>
            <div class="flex justify-center">
                <canvas id="divisionChart" width="220" height="220"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-1.5">
                @foreach($divisionLabels as $i => $label)
                <div class="flex items-center gap-2 text-xs">
                    <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $divisionColors[$i % count($divisionColors)] }}"></span>
                    <span class="text-slate-600 dark:text-slate-400 truncate">{{ $label }}</span>
                    <span class="ml-auto font-semibold text-slate-900 dark:text-white">{{ $divisionData[$i] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</section>

{{-- ================= BOTTOM ROW: RECENT + DIVISION PERFORMANCE ================= --}}
<section class="mb-8 grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Recent Activities Feed --}}
    <div class="erp-card p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">🕐 Aktivitas Terbaru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Log terkini dari seluruh pengguna</p>
            </div>
            <a href="{{ route('admin.activities') }}" class="text-xs font-medium text-[#d4af37] hover:text-[#b8941f] transition">
                Lihat Semua →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentActivities as $act)
            <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-700/50 hover:border-slate-200 dark:hover:border-slate-600 transition">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#f5e6b3] to-[#d4af37] flex items-center justify-center text-[10px] font-bold text-slate-900 shrink-0 mt-0.5">
                    {{ strtoupper(substr(optional($act->user)->name ?? '?', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ $act->deskripsi }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] text-slate-400">{{ optional($act->user)->name ?? 'System' }}</span>
                        <span class="text-[10px] text-slate-300 dark:text-slate-600">•</span>
                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($act->tanggal)->diffForHumans() }}</span>
                    </div>
                </div>
                @php
                    $sc = match($act->status) {
                        'completed','approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'submitted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                    };
                @endphp
                <span class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $sc }}">
                    {{ ucfirst($act->status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-8">Belum ada aktivitas tercatat</p>
            @endforelse
        </div>
    </div>

    {{-- Division Performance --}}
    <div class="erp-card p-6">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">🏆 Performa Divisi Bulan Ini</h3>
            <p class="text-xs text-slate-500 mt-0.5">Peringkat aktivitas per divisi — {{ now()->format('F Y') }}</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">#</th>
                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">Divisi</th>
                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Anggota</th>
                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Aktivitas</th>
                        <th class="px-4 py-3 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @foreach($divisionPerformance as $idx => $div)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="px-4 py-3">
                            @if($idx === 0)
                                <span class="text-base">🥇</span>
                            @elseif($idx === 1)
                                <span class="text-base">🥈</span>
                            @elseif($idx === 2)
                                <span class="text-base">🥉</span>
                            @else
                                <span class="text-xs font-bold text-slate-400">{{ $idx + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900 dark:text-white">{{ $div->nama_divisi }}</td>
                        <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">{{ $div->users_count }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $div->monthly_activities }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold
                                {{ $div->avg_per_user >= 3 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($div->avg_per_user >= 1 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400') }}">
                                {{ $div->avg_per_user }}/user
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</section>

{{-- ================= QUICK ACTIONS ================= --}}
<section class="mb-8">
    <div class="mb-5">
        <h3 class="text-base font-bold text-slate-900 dark:text-white">⚡ Quick Actions</h3>
        <p class="text-xs text-slate-500 mt-0.5">Akses cepat administrasi utama</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users') }}" class="erp-card p-5 group flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">👤</div>
            <div>
                <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Manage Users</h4>
                <p class="text-xs text-slate-500">{{ $totalUsers }} pengguna</p>
            </div>
        </a>
        <a href="{{ route('admin.activities') }}" class="erp-card p-5 group flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">📝</div>
            <div>
                <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Aktivitas</h4>
                <p class="text-xs text-slate-500">{{ $totalActivities }} log</p>
            </div>
        </a>
        <a href="{{ route('admin.reports') }}" class="erp-card p-5 group flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">📑</div>
            <div>
                <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Reports</h4>
                <p class="text-xs text-slate-500">{{ $totalReports }} laporan</p>
            </div>
        </a>
        <a href="{{ route('profile.index') }}" class="erp-card p-5 group flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#d4af37]/10 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">⚙️</div>
            <div>
                <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">Profil Saya</h4>
                <p class="text-xs text-slate-500">Pengaturan akun</p>
            </div>
        </a>
    </div>
</section>

{{-- ================= CHART SCRIPTS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(148,163,184,0.08)' : 'rgba(0,0,0,0.04)';
    const textColor = isDark ? '#64748b' : '#94a3b8';

    // ===== ACTIVITY LINE CHART =====
    const actCtx = document.getElementById('activityChart');
    if (actCtx) {
        new Chart(actCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: @json($chartDatasets)
            },
            options: {
                responsive: true,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#fff',
                        titleColor: isDark ? '#e2e8f0' : '#0f172a',
                        bodyColor: isDark ? '#94a3b8' : '#64748b',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: true,
                        boxPadding: 4,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} aktivitas`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { size: 11 } },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: textColor, font: { size: 11 }, padding: 8 },
                        grid: { color: gridColor, drawBorder: false },
                        border: { display: false }
                    }
                },
                elements: {
                    line: { borderWidth: 2.5 },
                    point: { radius: 4, hoverRadius: 7, hitRadius: 20 }
                }
            }
        });
    }

    // ===== DIVISION DOUGHNUT CHART =====
    const divCtx = document.getElementById('divisionChart');
    if (divCtx) {
        new Chart(divCtx, {
            type: 'doughnut',
            data: {
                labels: @json($divisionLabels),
                datasets: [{
                    data: @json($divisionData),
                    backgroundColor: @json($divisionColors),
                    borderWidth: isDark ? 2 : 3,
                    borderColor: isDark ? '#111a2e' : '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#fff',
                        titleColor: isDark ? '#e2e8f0' : '#0f172a',
                        bodyColor: isDark ? '#94a3b8' : '#64748b',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed} karyawan`
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection
