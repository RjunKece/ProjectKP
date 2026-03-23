@extends('layouts.admin')
@section('title', 'Reports Center')

@section('content')

{{-- ================= PAGE WRAPPER (ALPINE SCOPE) ================= --}}
<div x-data="{
    openReportModal: false,
    reportType: null
}">

    {{-- ================= PAGE HEADER ================= --}}
    <div class="mb-10">
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900">
            Reports Center
        </h1>
        <p class="text-slate-500 mt-2 max-w-2xl">
            Pusat analisis dan laporan strategis untuk pengambilan keputusan Super Admin.
        </p>
    </div>

    {{-- ================= KPI STRIP ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-10">

        <div class="kpi-card">
            <p>Total Reports</p>
            <h3>{{ $totalReports }}</h3>
        </div>

        <div class="kpi-card">
            <p>Generated (Month)</p>
            <h3 class="text-[#d4af37]">{{ $generatedThisMonth }}</h3>
        </div>

        <div class="kpi-card">
            <p>Scheduled</p>
            <h3 class="text-yellow-600">{{ $scheduledReports }}</h3>
        </div>

        <div class="kpi-card">
            <p>System Coverage</p>
            <h3 class="text-green-600">{{ $systemCoverage }}%</h3>
        </div>

        <div class="kpi-card">
            <p>Storage Used</p>
            <h3 class="text-purple-600">{{ $storageUsed }}</h3>
        </div>

    </div>

    {{-- ================= REPORT BUILDER ================= --}}
    <div class="erp-card p-8 mb-12">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    Report Builder
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pilih jenis laporan sesuai kebutuhan analisis
                </p>
            </div>

            <span
                class="px-4 py-1 rounded-full text-xs bg-[#f5e6b3] text-[#8a6d1d] font-medium">
                Super Admin Access
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- MODULE --}}
            <div class="report-card">
                <div class="icon">📊</div>
                <h4>Financial Intelligence</h4>
                <p>Revenue, profit, cost, dan performa keuangan perusahaan.</p>
                <button type="button"  @click="openReportModal = true; reportType = 'financial'">
                    Generate
                </button>
            </div>

            <div class="report-card">
                <div class="icon">🏢</div>
                <h4>Department Performance</h4>
                <p>Efisiensi, workload, dan kontribusi tiap divisi.</p>
                <button type="button" @click="openReportModal = true; reportType = 'department'">
                    Generate
                </button>
            </div>

            <div class="report-card">
                <div class="icon">📈</div>
                <h4>Growth & Trends</h4>
                <p>Analisis pertumbuhan jangka pendek & panjang.</p>
                <button type="button" @click="openReportModal = true; reportType = 'growth'">
                    Generate
                </button>
            </div>

            <div class="report-card">
                <div class="icon">🧩</div>
                <h4>Custom Intelligence</h4>
                <p>Bangun laporan khusus lintas modul ERP.</p>
                <button type="button"  @click="openReportModal = true; reportType = 'custom'">
                    Build
                </button>
            </div>

        </div>
    </div>

    {{-- ================= RECENT & SYSTEM INSIGHT ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- RECENT REPORTS --}}
        <div class="xl:col-span-2 erp-card overflow-hidden">

            <div
                class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-semibold text-slate-900">
                    Recent Reports
                </h3>
                <button type="button" class="btn-outline">
                    <a href="{{ route('admin.reports.archive') }}" class="btn btn-outline">
    View Archive
</a>

                </button>
            </div>

            <table class="w-full text-sm">
                <thead
                    class="bg-[#fafafa] text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="th">Report</th>
                        <th class="th">Type</th>
                        <th class="th">Period</th>
                        <th class="th">Status</th>
                        <th class="th text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">

                @forelse ($reports as $report)
                <tr class="hover:bg-slate-50 transition">
                    <td class="td font-medium text-slate-900">
                        {{ $report->title }}
                    </td>

                    <td class="td">
                        <span class="badge gold">
                            {{ ucfirst($report->type) }}
                        </span>
                    </td>

                    <td class="td text-slate-500">
                        {{ \Carbon\Carbon::parse($report->start_date)->format('M Y') }}
                    </td>

                    <td class="td">
                        @php
                            $statusColor = match($report->status) {
                                'ready' => 'green',
                                'generated' => 'gold',
                                default => 'gray',
                            };
                        @endphp

                        <span class="badge {{ $statusColor }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>

                    <td class="td text-right space-x-3">
                        <button type="button" class="action view">
                            <a href="{{ route('admin.reports.show', $report->id) }}">
    View
</a>
                        </button>

                        <button type="button" class="action download">
                            Download
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-400">
                        Belum ada laporan yang tersedia
                    </td>
                </tr>
                @endforelse

                </tbody>

            </table>
        </div>

        {{-- SYSTEM INSIGHT --}}
        <div class="erp-card p-6">

            <h3 class="font-semibold mb-6 text-slate-900">
                System Insight
            </h3>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">
                    <span class="text-slate-500">Most Used Report</span>
                    <span class="text-slate-900">Revenue Analysis</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Least Active Module</span>
                    <span class="text-yellow-600">HR Reports</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Last Generated</span>
                    <span class="text-slate-900">5 Jan 2025</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Automation Status</span>
                    <span class="text-green-600">Enabled</span>
                </div>

            </div>

            <button type="button" class="btn-primary w-full mt-8">
                Configure Automation
            </button>
        </div>

    </div>

    {{-- ================= MODAL (OPSIONAL) ================= --}}
    @include('admin.reports.modals.create')

</div>
{{-- ================= END PAGE WRAPPER ================= --}}

{{-- ================= STYLES ================= --}}
<style>
.kpi-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:1rem;
    padding:1.25rem;
}
.kpi-card p{font-size:.875rem;color:#6b7280}
.kpi-card h3{font-size:2rem;font-weight:700;margin-top:.5rem}

.report-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:1rem;
    padding:1.5rem;
    transition:.25s;
}
.report-card:hover{
    border-color:#d4af37;
    box-shadow:0 12px 28px rgba(212,175,55,.18);
}
.report-card .icon{font-size:1.75rem;margin-bottom:1rem}
.report-card h4{font-weight:600;color:#1f2937}
.report-card p{font-size:.875rem;color:#6b7280;margin:.5rem 0 1rem}
.report-card button{
    font-size:.875rem;
    padding:.5rem 1rem;
    border-radius:.5rem;
    background:linear-gradient(135deg,#f5e6b3,#d4af37);
    color:#1f2937;
    font-weight:600;
}

.th{padding:.75rem 1.5rem;text-align:left}
.td{padding:1rem 1.5rem}

.badge{padding:.25rem .75rem;border-radius:999px;font-size:.75rem}
.badge.green{background:rgba(22,163,74,.15);color:#16a34a}
.badge.gold{background:#f5e6b3;color:#8a6d1d}

.action{font-size:.875rem}
.action.view{color:#2563eb}
.action.download{color:#6b7280}

.btn-primary{
    background:linear-gradient(135deg,#f5e6b3,#d4af37);
    padding:.75rem 1rem;
    border-radius:.75rem;
    font-weight:600;
}
.btn-outline{
    padding:.5rem 1rem;
    border:1px solid #e5e7eb;
    border-radius:.75rem;
    color:#374151;
}
</style>
</section>
</div> 
@endsection
