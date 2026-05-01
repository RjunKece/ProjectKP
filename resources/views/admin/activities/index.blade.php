@extends('layouts.admin')
@section('title', 'Monitoring Aktivitas')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
            Monitoring Aktivitas
        </h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Pemantauan dan riwayat aktivitas seluruh pengguna ERP
        </p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center gap-2.5 px-3.5 py-2 rounded-xl bg-green-50 dark:bg-green-900/15 border border-green-100 dark:border-green-800">
        <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
        </span>
        <span class="text-xs font-semibold text-green-700 dark:text-green-400">Live Monitoring</span>
    </div>
</div>

{{-- ================= KPI CARDS ================= --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">

    <div class="erp-card p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-blue-100/60 to-transparent dark:from-blue-900/15 rounded-bl-[2rem]"></div>
        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Aktivitas</p>
        <h3 class="text-2xl font-extrabold mt-2 text-slate-900 dark:text-white">{{ number_format($totalActivities) }}</h3>
        <p class="text-[10px] text-slate-500 mt-1">Seluruh log</p>
    </div>

    <div class="erp-card p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-amber-100/60 to-transparent dark:from-amber-900/15 rounded-bl-[2rem]"></div>
        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Hari Ini</p>
        <h3 class="text-2xl font-extrabold mt-2 text-[#d4af37]">{{ $todayActivities }}</h3>
        <p class="text-[10px] text-slate-500 mt-1">{{ now()->format('d M Y') }}</p>
    </div>

    <div class="erp-card p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-indigo-100/60 to-transparent dark:from-indigo-900/15 rounded-bl-[2rem]"></div>
        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">7 Hari Terakhir</p>
        <h3 class="text-2xl font-extrabold mt-2 text-indigo-600">{{ $weekActivities }}</h3>
        <p class="text-[10px] text-slate-500 mt-1">Tren mingguan</p>
    </div>

    <div class="erp-card p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-purple-100/60 to-transparent dark:from-purple-900/15 rounded-bl-[2rem]"></div>
        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Admin</p>
        <h3 class="text-2xl font-extrabold mt-2 text-purple-600">{{ $adminActivities }}</h3>
        <p class="text-[10px] text-slate-500 mt-1">Administratif</p>
    </div>

    <div class="erp-card p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-green-100/60 to-transparent dark:from-green-900/15 rounded-bl-[2rem]"></div>
        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Karyawan</p>
        <h3 class="text-2xl font-extrabold mt-2 text-green-600">{{ $employeeActivities }}</h3>
        <p class="text-[10px] text-slate-500 mt-1">Operasional</p>
    </div>

</div>

{{-- ================= CHARTS ROW ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

    {{-- Weekly Trend Chart (Chart.js) --}}
    <div class="erp-card p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">📈 Tren 7 Hari Terakhir</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Grafik aktivitas harian seluruh pengguna</p>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 dark:bg-slate-800/60">
                <span class="w-2 h-2 rounded-full bg-gradient-to-r from-[#d4af37] to-[#f5e6b3]"></span>
                <span class="text-[10px] text-slate-500 font-medium">Aktivitas</span>
            </div>
        </div>
        <div style="height: 180px;">
            <canvas id="weeklyTrendChart"></canvas>
        </div>
    </div>

    {{-- Status Breakdown (Doughnut + List) --}}
    <div class="erp-card p-5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">📊 Status Breakdown</h3>
        <div class="flex justify-center mb-4">
            <div style="width:120px;height:120px;">
                <canvas id="statusDonutChart"></canvas>
            </div>
        </div>
        <div class="space-y-2.5">
            @php
                $statusConfig = [
                    'submitted'   => ['label' => 'Submitted', 'color' => 'bg-blue-500', 'dot' => '#3b82f6', 'text' => 'text-blue-600 dark:text-blue-400'],
                    'in_progress' => ['label' => 'In Progress', 'color' => 'bg-amber-500', 'dot' => '#f59e0b', 'text' => 'text-amber-600 dark:text-amber-400'],
                    'completed'   => ['label' => 'Completed', 'color' => 'bg-green-500', 'dot' => '#22c55e', 'text' => 'text-green-600 dark:text-green-400'],
                    'approved'    => ['label' => 'Approved', 'color' => 'bg-emerald-500', 'dot' => '#10b981', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                    'cancelled'   => ['label' => 'Cancelled', 'color' => 'bg-red-500', 'dot' => '#ef4444', 'text' => 'text-red-600 dark:text-red-400'],
                ];
                $totalForBar = $totalActivities ?: 1;
            @endphp
            @forelse($statusCounts as $status => $count)
                @php $cfg = $statusConfig[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-slate-500', 'dot' => '#64748b', 'text' => 'text-slate-600']; @endphp
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full {{ $cfg['color'] }} shrink-0"></span>
                    <span class="text-xs font-medium {{ $cfg['text'] }} flex-1">{{ $cfg['label'] }}</span>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $count }}</span>
                    <span class="text-[10px] text-slate-400">({{ round(($count / $totalForBar) * 100) }}%)</span>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 text-center">Belum ada data</p>
            @endforelse
        </div>
    </div>

</div>

{{-- ================= TOP PERFORMERS + HEATMAP ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

    {{-- Top Performers --}}
    <div class="erp-card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">🏆 Top Performers Bulan Ini</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Karyawan paling aktif berdasarkan jumlah aktivitas</p>
            </div>
            <span class="text-[10px] font-semibold text-[#d4af37] bg-[#d4af37]/10 px-2 py-0.5 rounded-md">{{ now()->format('M Y') }}</span>
        </div>
        <div class="space-y-2.5">
            @php
                $topPerformers = \App\Models\Activity::selectRaw('user_id, COUNT(*) as total')
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->groupBy('user_id')
                    ->orderByDesc('total')
                    ->limit(5)
                    ->with('user.division')
                    ->get();
                $topMax = $topPerformers->first()->total ?? 1;
                $medals = ['🥇','🥈','🥉','4','5'];
            @endphp
            @forelse($topPerformers as $i => $tp)
                <div class="flex items-center gap-3 p-2.5 rounded-xl {{ $i === 0 ? 'bg-gradient-to-r from-[#d4af37]/5 to-transparent border border-[#d4af37]/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800/30' }} transition">
                    <span class="text-base w-6 text-center shrink-0">{{ $medals[$i] ?? ($i+1) }}</span>
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-[11px] font-bold shrink-0">
                        {{ strtoupper(substr(optional($tp->user)->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ optional($tp->user)->name ?? 'Unknown' }}</p>
                        <p class="text-[10px] text-slate-400">{{ optional(optional($tp->user)->division)->nama_divisi ?? '—' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ $tp->total }}</p>
                        <p class="text-[10px] text-slate-400">aktivitas</p>
                    </div>
                    <div class="w-16 shrink-0">
                        <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-700"
                                 style="width:{{ ($tp->total / $topMax) * 100 }}%;background:linear-gradient(90deg,#d4af37,#f5e6b3);"></div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada data bulan ini</p>
            @endforelse
        </div>
    </div>

    {{-- Activity Heatmap --}}
    <div class="erp-card p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">🔥 Activity Heatmap</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Intensitas aktivitas 30 hari terakhir</p>
            </div>
            <div class="flex items-center gap-1">
                <span class="text-[9px] text-slate-400">Less</span>
                <span class="w-3 h-3 rounded-sm bg-slate-100 dark:bg-slate-700"></span>
                <span class="w-3 h-3 rounded-sm bg-[#f5e6b3]"></span>
                <span class="w-3 h-3 rounded-sm bg-[#e8c84a]"></span>
                <span class="w-3 h-3 rounded-sm bg-[#d4af37]"></span>
                <span class="w-3 h-3 rounded-sm bg-[#a08520]"></span>
                <span class="text-[9px] text-slate-400">More</span>
            </div>
        </div>
        @php
            $heatmap = [];
            $heatMax = 1;
            for ($i = 29; $i >= 0; $i--) {
                $d = now()->subDays($i)->toDateString();
                $c = \App\Models\Activity::whereDate('tanggal', $d)->count();
                $heatmap[] = ['date' => $d, 'count' => $c, 'label' => \Carbon\Carbon::parse($d)->format('d M')];
                if ($c > $heatMax) $heatMax = $c;
            }
        @endphp
        <div class="grid grid-cols-10 gap-1.5">
            @foreach($heatmap as $cell)
                @php
                    $intensity = $heatMax > 0 ? $cell['count'] / $heatMax : 0;
                    if ($cell['count'] == 0) $bg = 'background:#f1f5f9;';
                    elseif ($intensity <= 0.25) $bg = 'background:#f5e6b3;';
                    elseif ($intensity <= 0.5) $bg = 'background:#e8c84a;';
                    elseif ($intensity <= 0.75) $bg = 'background:#d4af37;';
                    else $bg = 'background:#a08520;';
                @endphp
                <div class="aspect-square rounded-sm relative group cursor-default" style="{{ $bg }}">
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 rounded-md bg-slate-900 text-white text-[9px] whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none z-10">
                        {{ $cell['label'] }}: {{ $cell['count'] }} aktivitas
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 grid grid-cols-3 gap-3">
            @php
                $totalMonth = array_sum(array_column($heatmap, 'count'));
                $avgDay = count($heatmap) > 0 ? round($totalMonth / count($heatmap), 1) : 0;
                $activeDays = count(array_filter($heatmap, fn($h) => $h['count'] > 0));
            @endphp
            <div class="text-center p-2 rounded-lg bg-slate-50 dark:bg-slate-800/40">
                <p class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $totalMonth }}</p>
                <p class="text-[10px] text-slate-400">Total 30 Hari</p>
            </div>
            <div class="text-center p-2 rounded-lg bg-slate-50 dark:bg-slate-800/40">
                <p class="text-lg font-extrabold text-[#d4af37]">{{ $avgDay }}</p>
                <p class="text-[10px] text-slate-400">Rata-rata/Hari</p>
            </div>
            <div class="text-center p-2 rounded-lg bg-slate-50 dark:bg-slate-800/40">
                <p class="text-lg font-extrabold text-green-600">{{ $activeDays }}</p>
                <p class="text-[10px] text-slate-400">Hari Aktif</p>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(148,163,184,0.08)' : 'rgba(0,0,0,0.04)';

    // Weekly Trend Bar Chart
    const weeklyCtx = document.getElementById('weeklyTrendChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($weeklyTrend, 'day')) !!},
                datasets: [{
                    data: {!! json_encode(array_column($weeklyTrend, 'count')) !!},
                    backgroundColor: function(ctx) {
                        const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 180);
                        g.addColorStop(0, '#d4af37');
                        g.addColorStop(1, '#f5e6b3');
                        return g;
                    },
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: {
                    backgroundColor: '#0f172a', titleColor: '#f5e6b3', bodyColor: '#fff',
                    cornerRadius: 8, padding: 10, displayColors: false,
                    callbacks: { label: ctx => ctx.parsed.y + ' aktivitas' }
                }},
                scales: {
                    y: { beginAtZero: true, ticks: { color: textColor, font: { size: 10 }, stepSize: 1 }, grid: { color: gridColor } },
                    x: { ticks: { color: textColor, font: { size: 10 } }, grid: { display: false } }
                }
            }
        });
    }

    // Status Donut Chart
    const donutCtx = document.getElementById('statusDonutChart');
    if (donutCtx) {
        const statusData = {!! json_encode(array_values($statusCounts)) !!};
        const statusLabels = {!! json_encode(array_map(fn($s) => ucfirst(str_replace('_',' ',$s)), array_keys($statusCounts))) !!};
        const statusColors = {!! json_encode(array_map(fn($s) => ($statusConfig[$s] ?? ['dot'=>'#64748b'])['dot'], array_keys($statusCounts))) !!};
        if (statusData.length > 0) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0, hoverOffset: 4 }] },
                options: {
                    responsive: true, maintainAspectRatio: true, cutout: '65%',
                    plugins: { legend: { display: false }, tooltip: {
                        backgroundColor: '#0f172a', bodyColor: '#fff', cornerRadius: 8, padding: 8, displayColors: true
                    }}
                }
            });
        }
    }
});
</script>
@endpush

{{-- ================= FILTER BAR ================= --}}
<form method="GET" class="erp-card p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari user atau aktivitas..." class="input w-full">
        </div>
        <div class="w-36">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Role</label>
            <select name="role_id" class="input w-full">
                <option value="">Semua</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $role->nama_role)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Divisi</label>
            <select name="division_id" class="input w-full">
                <option value="">Semua</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->nama_divisi }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Status</label>
            <select name="status" class="input w-full">
                <option value="">Semua</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            </select>
        </div>
        <div class="w-36">
            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1 block">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}" class="input w-full">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
            <a href="{{ route('admin.activities') }}" class="btn-secondary px-3 py-2 text-sm">Reset</a>
        </div>
    </div>
</form>

{{-- ================= ACTIVITY TABLE ================= --}}
<div class="erp-card overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/80 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-700">
                    <th class="px-5 py-3.5 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">#</th>
                    <th class="px-5 py-3.5 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">User</th>
                    <th class="px-5 py-3.5 text-left text-[10px] uppercase tracking-wider font-semibold text-slate-400">Deskripsi</th>
                    <th class="px-5 py-3.5 text-center text-[10px] uppercase tracking-wider font-semibold text-slate-400">Status</th>
                    <th class="px-5 py-3.5 text-right text-[10px] uppercase tracking-wider font-semibold text-slate-400">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @forelse ($activities as $index => $activity)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                    <td class="px-5 py-3.5 text-slate-400 text-xs font-medium">
                        {{ $activities->firstItem() + $index }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @php
                                $isAdmin = optional(optional($activity->user)->role)->nama_role === 'super_admin';
                                $initials = strtoupper(substr(optional($activity->user)->name ?? '?', 0, 1));
                            @endphp
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-bold shrink-0
                                        {{ $isAdmin ? 'bg-gradient-to-br from-[#f5e6b3] to-[#d4af37] text-slate-900' : 'bg-gradient-to-br from-blue-400 to-blue-600 text-white' }}">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-900 dark:text-white text-sm truncate">
                                    {{ optional($activity->user)->name ?? 'Deleted User' }}
                                </p>
                                <p class="text-[10px] text-slate-400 truncate">
                                    {{ optional(optional($activity->user)->division)->nama_divisi ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="text-slate-700 dark:text-slate-300 truncate max-w-xs" title="{{ $activity->deskripsi }}">
                            {{ $activity->deskripsi }}
                        </p>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @php
                            $statusStyles = [
                                'submitted'   => 'bg-blue-50 text-blue-700 dark:bg-blue-900/25 dark:text-blue-400 border-blue-100 dark:border-blue-800',
                                'in_progress' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/25 dark:text-amber-400 border-amber-100 dark:border-amber-800',
                                'completed'   => 'bg-green-50 text-green-700 dark:bg-green-900/25 dark:text-green-400 border-green-100 dark:border-green-800',
                                'approved'    => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/25 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
                                'cancelled'   => 'bg-red-50 text-red-700 dark:bg-red-900/25 dark:text-red-400 border-red-100 dark:border-red-800',
                            ];
                            $style = $statusStyles[$activity->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-md text-[10px] font-bold border {{ $style }}">
                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300">
                            {{ $activity->tanggal->format('d M Y') }}
                        </p>
                        <p class="text-[10px] text-slate-400">{{ $activity->tanggal->format('H:i') }} WIB</p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">📭</div>
                            <div>
                                <p class="text-slate-600 dark:text-slate-300 font-medium">Belum ada aktivitas tercatat</p>
                                <p class="text-xs text-slate-400 mt-1">Aktivitas akan muncul setelah sistem digunakan</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= PAGINATION ================= --}}
@if($activities->hasPages())
    <div class="flex justify-between items-center mb-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">
            Menampilkan {{ $activities->firstItem() }}–{{ $activities->lastItem() }} dari {{ $activities->total() }} aktivitas
        </p>
        <div class="flex gap-1">
            @if($activities->onFirstPage())
                <span class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">←</span>
            @else
                <a href="{{ $activities->previousPageUrl() }}" class="px-3 py-1.5 text-xs rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-[#d4af37] transition">←</a>
            @endif
            @foreach($activities->getUrlRange(max(1, $activities->currentPage()-2), min($activities->lastPage(), $activities->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}"
                   class="px-3 py-1.5 text-xs rounded-lg border transition
                          {{ $page == $activities->currentPage()
                              ? 'bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] border-[#d4af37] font-bold text-slate-900'
                              : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-[#d4af37] text-slate-600 dark:text-slate-300' }}">
                    {{ $page }}
                </a>
            @endforeach
            @if($activities->hasMorePages())
                <a href="{{ $activities->nextPageUrl() }}" class="px-3 py-1.5 text-xs rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-[#d4af37] transition">→</a>
            @else
                <span class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">→</span>
            @endif
        </div>
    </div>
@endif

@endsection
