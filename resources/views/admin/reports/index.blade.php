@extends('layouts.admin')
@section('title', 'Reports Center')

@section('content')

{{-- ================= PAGE WRAPPER (ALPINE SCOPE) ================= --}}
<div x-data="{
    openReportModal: false,
    reportType: null,
    showViewModal: false,
    viewReport: null,
    loading: false,
    showDeleteModal: false,
    deleteReportId: null,
    deleteReportTitle: '',

    async viewReportPopup(id) {
        this.loading = true;
        this.showViewModal = true;
        try {
            const res = await fetch('/admin/reports/' + id, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            this.viewReport = await res.json();
        } catch(e) {
            this.viewReport = { title: 'Error', description: 'Gagal memuat laporan.' };
        }
        this.loading = false;
    },
    confirmDelete(id, title) {
        this.deleteReportId = id;
        this.deleteReportTitle = title;
        this.showDeleteModal = true;
    }
}">

    {{-- ================= NOTIFICATIONS ================= --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition
             class="mb-6 px-5 py-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
            <button @click="show = false" class="text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif

    {{-- ================= PAGE HEADER ================= --}}
    <div class="mb-10">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
            Reports Center
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Pusat analisis dan laporan strategis — kelola laporan perusahaan & divisi
        </p>
    </div>

    {{-- ================= KPI STRIP ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5 mb-10">

        <div class="erp-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-blue-100 to-transparent dark:from-blue-900/20 rounded-bl-full"></div>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Reports</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-white">{{ $totalReports }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Seluruh laporan</p>
        </div>

        <div class="erp-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-amber-100 to-transparent dark:from-amber-900/20 rounded-bl-full"></div>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Bulan Ini</p>
            <h3 class="text-2xl font-bold mt-1 text-[#d4af37]">{{ $generatedThisMonth }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Dibuat bulan ini</p>
        </div>

        <div class="erp-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-emerald-100 to-transparent dark:from-emerald-900/20 rounded-bl-full"></div>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Perusahaan</p>
            <h3 class="text-2xl font-bold mt-1 text-emerald-600">{{ $companyReports }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Laporan perusahaan</p>
        </div>

        <div class="erp-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-indigo-100 to-transparent dark:from-indigo-900/20 rounded-bl-full"></div>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Divisi</p>
            <h3 class="text-2xl font-bold mt-1 text-indigo-600">{{ $divisionReports }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Laporan per divisi</p>
        </div>

        <div class="erp-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-yellow-100 to-transparent dark:from-yellow-900/20 rounded-bl-full"></div>
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Pending</p>
            <h3 class="text-2xl font-bold mt-1 text-yellow-600">{{ $pendingReports }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Menunggu review</p>
        </div>

    </div>

    {{-- ================= FILTER BAR ================= --}}
    <div class="erp-card p-4 mb-6">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="input flex-1 min-w-[200px]" placeholder="🔍 Cari laporan...">
            <select name="scope" class="input w-auto min-w-[160px]">
                <option value="">Semua Scope</option>
                <option value="company" {{ request('scope') === 'company' ? 'selected' : '' }}>🏛️ Perusahaan</option>
                <option value="division" {{ request('scope') === 'division' ? 'selected' : '' }}>🏢 Divisi</option>
            </select>
            <select name="division_id" class="input w-auto min-w-[160px]">
                <option value="">Semua Divisi</option>
                @foreach($divisions as $div)
                    <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>
                        {{ $div->nama_divisi }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold
                                         bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                         hover:opacity-90 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'scope', 'division_id']))
                <a href="{{ route('admin.reports') }}" class="text-xs text-slate-500 hover:text-red-500 transition">✕ Reset</a>
            @endif
        </form>
    </div>

    {{-- ================= REPORT BUILDER ================= --}}
    <div class="erp-card p-8 mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Report Builder</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Pilih jenis laporan sesuai kebutuhan analisis</p>
            </div>
            <span class="px-4 py-1 rounded-full text-xs bg-[#f5e6b3] text-[#8a6d1d] font-medium">Super Admin Access</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            @php
                $types = [
                    ['key' => 'financial',  'icon' => '📊', 'title' => 'Financial Intelligence',  'desc' => 'Revenue, profit, cost, dan performa keuangan.'],
                    ['key' => 'department', 'icon' => '🏢', 'title' => 'Department Performance',  'desc' => 'Efisiensi, workload, dan kontribusi tiap divisi.'],
                    ['key' => 'growth',     'icon' => '📈', 'title' => 'Growth & Trends',         'desc' => 'Analisis pertumbuhan jangka pendek & panjang.'],
                    ['key' => 'custom',     'icon' => '🧩', 'title' => 'Custom Intelligence',     'desc' => 'Bangun laporan khusus lintas modul ERP.'],
                ];
            @endphp

            @foreach($types as $t)
                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-6
                            hover:border-[#d4af37] hover:shadow-lg hover:shadow-amber-100/50 transition-all duration-300 group">
                    <div class="text-3xl mb-3">{{ $t['icon'] }}</div>
                    <h4 class="font-semibold text-slate-900 dark:text-white group-hover:text-[#d4af37] transition">{{ $t['title'] }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 mb-4">{{ $t['desc'] }}</p>
                    <button type="button"
                            @click="openReportModal = true; reportType = '{{ $t['key'] }}'"
                            class="w-full py-2 rounded-lg text-sm font-semibold
                                   bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                                   hover:opacity-90 transition">
                        Generate
                    </button>
                </div>
            @endforeach

        </div>
    </div>

    {{-- ================= RECENT REPORTS TABLE ================= --}}
    <div class="erp-card overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-semibold text-slate-900 dark:text-white">Semua Laporan</h3>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="px-5 py-3 text-left text-xs">#</th>
                    <th class="px-5 py-3 text-left text-xs">Report</th>
                    <th class="px-5 py-3 text-left text-xs">Scope</th>
                    <th class="px-5 py-3 text-left text-xs">Type</th>
                    <th class="px-5 py-3 text-left text-xs">Creator</th>
                    <th class="px-5 py-3 text-left text-xs">Tanggal</th>
                    <th class="px-5 py-3 text-left text-xs">Respon</th>
                    <th class="px-5 py-3 text-left text-xs">Status</th>
                    <th class="px-5 py-3 text-right text-xs">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">

                @forelse ($reports as $index => $report)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $reports->firstItem() + $index }}</td>

                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-900 dark:text-white text-xs">{{ $report->title }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[200px]">{{ Str::limit($report->description, 50) }}</p>
                        </td>

                        <td class="px-5 py-3">
                            @if($report->scope === 'company')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    🏛️ Perusahaan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    🏢 {{ optional($report->division)->nama_divisi ?? 'Divisi' }}
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-3">
                            @php
                                $typeIcons = ['financial' => '📊', 'department' => '🏢', 'growth' => '📈', 'custom' => '🧩',
                                              'kebutuhan' => '📋', 'kendala' => '⚠️', 'saran' => '💡', 'progress' => '📈', 'lainnya' => '📄'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#f5e6b3] text-[#8a6d1d]">
                                {{ $typeIcons[$report->type] ?? '📄' }} {{ ucfirst($report->type) }}
                            </span>
                        </td>

                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs">
                            {{ optional($report->creator)->name ?? 'System' }}
                        </td>

                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs whitespace-nowrap">
                            {{ $report->start_date ? \Carbon\Carbon::parse($report->start_date)->format('d M Y') : '-' }}
                        </td>

                        <td class="px-5 py-3">
                            @php $resCount = $report->responses->count(); @endphp
                            @if($resCount > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    💬 {{ $resCount }}
                                </span>
                            @else
                                <span class="text-[10px] text-slate-400">—</span>
                            @endif
                        </td>

                        <td class="px-5 py-3">
                            @php
                                $sCfg = match($report->status) {
                                    'ready' => ['bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300', '✅'],
                                    'generated' => ['bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300', '⏳'],
                                    'failed' => ['bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300', '❌'],
                                    default => ['bg-slate-100 text-slate-600', '📌'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sCfg[0] }}">
                                {{ $sCfg[1] }} {{ ucfirst($report->status) }}
                            </span>
                        </td>

                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button"
                                        @click="viewReportPopup({{ $report->id }})"
                                        class="px-3 py-1 rounded-lg text-xs font-medium
                                               bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300
                                               hover:bg-blue-100 dark:hover:bg-blue-900/60 transition">
                                    👁 View
                                </button>
                                <button type="button"
                                        @click="confirmDelete({{ $report->id }}, '{{ addslashes($report->title) }}')"
                                        class="px-3 py-1 rounded-lg text-xs font-medium
                                               bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300
                                               hover:bg-red-100 dark:hover:bg-red-900/60 transition">
                                    🗑 Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">📭</div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada laporan</p>
                                <p class="text-xs text-slate-400">Klik "Generate" di atas untuk membuat laporan baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- ================= PAGINATION ================= --}}
    @if($reports->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $reports->links() }}
        </div>
    @endif

    {{-- ================= VIEW REPORT POPUP ================= --}}
    <div x-show="showViewModal" x-transition.opacity x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showViewModal = false"></div>

        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col z-[10000] border border-slate-200 dark:border-slate-700"
             @click.stop>

            {{-- Header --}}
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between shrink-0">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white" x-text="viewReport?.title || 'Loading...'"></h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Preview Laporan</p>
                </div>
                <button @click="showViewModal = false" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700 transition">✕</button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto p-6">

                {{-- Loading --}}
                <div x-show="loading" class="flex items-center justify-center py-12">
                    <div class="w-8 h-8 border-3 border-[#d4af37] border-t-transparent rounded-full animate-spin"></div>
                </div>

                <div x-show="!loading && viewReport" class="space-y-6">

                    {{-- Report Header --}}
                    <div class="bg-gradient-to-r from-[#fffdf7] to-[#f5e6b3]/30 dark:from-slate-800 dark:to-slate-800 border border-[#f5e6b3] dark:border-slate-700 rounded-xl p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight" x-text="viewReport?.title"></h3>
                            <div class="flex justify-center items-center gap-3 mt-2">
                                <span class="h-[2px] w-10 bg-[#f5e6b3]"></span>
                                <span class="h-[3px] w-16 bg-[#d4af37] rounded-full"></span>
                                <span class="h-[2px] w-10 bg-[#f5e6b3]"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Scope</span>
                                <p class="font-medium text-slate-900 dark:text-white capitalize" x-text="viewReport?.scope === 'company' ? '🏛️ Perusahaan' : '🏢 Divisi: ' + (viewReport?.division || '')"></p>
                            </div>
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Status</span>
                                <p class="font-medium capitalize" x-text="viewReport?.status"
                                   :class="viewReport?.status === 'ready' ? 'text-green-600' : 'text-amber-600'"></p>
                            </div>
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Tanggal Laporan</span>
                                <p class="font-medium text-slate-900 dark:text-white" x-text="viewReport?.start_date"></p>
                            </div>
                            <div>
                                <span class="text-slate-500 dark:text-slate-400">Dibuat Oleh</span>
                                <p class="font-medium text-slate-900 dark:text-white" x-text="viewReport?.creator"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">📋 Isi Laporan</h4>
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-5 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line"
                             x-text="viewReport?.description || 'Tidak ada deskripsi.'"></div>
                    </div>

                    {{-- Responses --}}
                    <template x-if="viewReport?.responses && viewReport.responses.length > 0">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">💬 Tanggapan (<span x-text="viewReport.responses.length"></span>)</h4>
                            <div class="space-y-3">
                                <template x-for="res in viewReport.responses" :key="res.id">
                                    <div class="flex gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-700 dark:text-blue-300 shrink-0"
                                             x-text="res.user.charAt(0).toUpperCase()"></div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-semibold text-slate-900 dark:text-white" x-text="res.user"></span>
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-medium"
                                                      :class="res.type === 'reply' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400'"
                                                      x-text="res.type === 'reply' ? 'Reply' : 'Dibaca'"></span>
                                                <span class="text-[10px] text-slate-400" x-text="res.created_at"></span>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-400" x-text="res.message"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Footer Info --}}
                    <div class="text-xs text-slate-400 text-right">
                        Dibuat pada: <span x-text="viewReport?.created_at"></span>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="p-5 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3 shrink-0">
                <button @click="showViewModal = false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-slate-600
                               hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Tutup
                </button>
                <button @click="window.print()"
                        class="px-4 py-2 text-sm rounded-lg font-semibold
                               bg-gradient-to-r from-[#f5e6b3] to-[#d4af37] text-slate-900
                               hover:opacity-90 transition">
                    🖨 Print
                </button>
            </div>
        </div>
    </div>

    {{-- ================= CREATE REPORT MODAL ================= --}}
    @include('admin.reports.modals.create')

    {{-- ================= DELETE CONFIRMATION MODAL ================= --}}
    <div x-show="showDeleteModal" x-transition.opacity x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 z-[10000] border border-slate-200 dark:border-slate-700"
             @click.stop>
            <div class="mx-auto mb-4 w-14 h-14 flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 text-2xl">
                🗑️
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-2">Hapus Laporan?</h3>
            <p class="text-sm text-slate-500 text-center mb-2">Apakah Anda yakin ingin menghapus laporan:</p>
            <p class="text-sm font-semibold text-red-600 text-center mb-4" x-text="deleteReportTitle"></p>
            <p class="text-xs text-slate-400 text-center mb-6">Tindakan ini tidak dapat dibatalkan. Semua tanggapan terkait juga akan dihapus.</p>

            <div class="flex justify-center gap-3">
                <button @click="showDeleteModal = false"
                        class="px-5 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    Batal
                </button>
                <form :action="'/admin/reports/' + deleteReportId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 text-sm rounded-xl font-semibold bg-red-600 text-white hover:bg-red-700 shadow-md transition">
                        Ya, Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
