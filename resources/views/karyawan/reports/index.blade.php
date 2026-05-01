@extends('layouts.karyawan')
@section('title', 'Laporan')

@section('content')
<div x-data="{ activeTab: '{{ $tab }}', showCreateModal: false }">

    {{-- NOTIFICATIONS --}}
    @if(session('success'))
        <div x-data="{ s: true }" x-show="s" x-init="setTimeout(() => s = false, 4000)" x-transition
             class="mb-6 px-5 py-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
            <button @click="s = false" class="text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif

    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pusat Laporan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola dan pantau laporan perusahaan & divisi</p>
        </div>
        <button @click="showCreateModal = true"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                       bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25
                       hover:shadow-blue-500/40 hover:translate-y-[-1px] transition-all">
            ✍️ Buat Laporan Baru
        </button>
    </div>

    {{-- KPI STRIP --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="erp-card p-5">
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Perusahaan</p>
            <h3 class="text-2xl font-bold mt-1 text-emerald-600">{{ $totalCompany }}</h3>
            <p class="text-[11px] text-slate-500">Pengumuman & info</p>
        </div>
        <div class="erp-card p-5">
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Divisi Saya</p>
            <h3 class="text-2xl font-bold mt-1 text-blue-600">{{ $totalDivision }}</h3>
            <p class="text-[11px] text-slate-500">Tugas & laporan divisi</p>
        </div>
        <div class="erp-card p-5">
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Laporan Saya</p>
            <h3 class="text-2xl font-bold mt-1 text-[#d4af37]">{{ $totalMyReports }}</h3>
            <p class="text-[11px] text-slate-500">Dibuat oleh Anda</p>
        </div>
        <div class="erp-card p-5">
            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Belum Dibaca</p>
            <h3 class="text-2xl font-bold mt-1 text-red-500">{{ $unreadCompany }}</h3>
            <p class="text-[11px] text-slate-500">Perlu perhatian</p>
        </div>
    </div>

    {{-- TABS --}}
    <div class="flex gap-1 mb-6 bg-slate-100 dark:bg-slate-800 rounded-xl p-1.5 w-fit">
        <button @click="activeTab = 'company'" :class="activeTab === 'company' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            🏛️ Perusahaan
            @if($unreadCompany > 0)
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white">{{ $unreadCompany }}</span>
            @endif
        </button>
        <button @click="activeTab = 'division'" :class="activeTab === 'division' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            🏢 Divisi Saya
        </button>
        <button @click="activeTab = 'my'" :class="activeTab === 'my' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 rounded-lg text-sm transition-all">
            📄 Laporan Saya
        </button>
    </div>

    {{-- ==================== TAB: PERUSAHAAN ==================== --}}
    <div x-show="activeTab === 'company'" x-transition.opacity>
        <div class="erp-card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-semibold text-slate-900 dark:text-white">📢 Laporan Perusahaan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pengumuman dan informasi penting dari manajemen</p>
            </div>
            @if($companyReports->count())
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($companyReports as $r)
                @php
                    $isRead = $r->responses->where('user_id', auth()->id())->count() > 0;
                    $prioColor = match($r->priority) {
                        'urgent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'high' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                        'normal' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400',
                        default => 'bg-slate-50 text-slate-500',
                    };
                @endphp
                <a href="{{ route('karyawan.reports.show', $r->id) }}"
                   class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0
                                {{ $isRead ? 'bg-slate-100 dark:bg-slate-800' : 'bg-blue-100 dark:bg-blue-900/30' }}">
                        {{ $isRead ? '✅' : '📩' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate group-hover:text-blue-600 transition {{ !$isRead ? 'font-bold' : '' }}">
                                {{ $r->title }}
                            </p>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $prioColor }}">{{ ucfirst($r->priority) }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ Str::limit($r->description, 80) }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[11px] text-slate-400">{{ $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('d M Y') : '' }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ optional($r->creator)->name }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @if($companyReports->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">{{ $companyReports->appends(['tab' => 'company'])->links() }}</div>
            @endif
            @else
            <div class="px-6 py-16 text-center">
                <div class="text-4xl mb-3">📭</div>
                <p class="text-slate-500 font-medium">Belum ada pengumuman perusahaan</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ==================== TAB: DIVISI ==================== --}}
    <div x-show="activeTab === 'division'" x-transition.opacity>
        <div class="erp-card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-semibold text-slate-900 dark:text-white">🏢 Laporan Divisi — {{ optional(auth()->user()->division)->nama_divisi ?? 'Belum ada divisi' }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tugas dan informasi khusus untuk divisi Anda</p>
            </div>
            @if($divisionReports instanceof \Illuminate\Pagination\LengthAwarePaginator && $divisionReports->count())
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($divisionReports as $r)
                @php $isRead = $r->responses->where('user_id', auth()->id())->count() > 0; @endphp
                <a href="{{ route('karyawan.reports.show', $r->id) }}"
                   class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0
                                {{ $isRead ? 'bg-slate-100 dark:bg-slate-800' : 'bg-indigo-100 dark:bg-indigo-900/30' }}">
                        {{ $isRead ? '✅' : '📋' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate group-hover:text-blue-600 transition {{ !$isRead ? 'font-bold' : '' }}">
                            {{ $r->title }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ Str::limit($r->description, 80) }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[11px] text-slate-400">{{ $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('d M Y') : '' }}</p>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 font-medium">💬 {{ $r->responses_count }}</span>
                    </div>
                </a>
                @endforeach
            </div>
            @if($divisionReports->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">{{ $divisionReports->appends(['tab' => 'division'])->links() }}</div>
            @endif
            @else
            <div class="px-6 py-16 text-center">
                <div class="text-4xl mb-3">📂</div>
                <p class="text-slate-500 font-medium">Belum ada laporan untuk divisi Anda</p>
                <p class="text-xs text-slate-400 mt-1">Laporan divisi akan muncul setelah admin menerbitkannya</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ==================== TAB: LAPORAN SAYA ==================== --}}
    <div x-show="activeTab === 'my'" x-transition.opacity>
        <div class="erp-card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">📄 Laporan yang Saya Buat</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Laporan kebutuhan, kendala, saran, dan progress Anda</p>
                </div>
            </div>
            @if($myReports->count())
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-slate-500 text-xs">
                    <tr>
                        <th class="px-5 py-3 text-left">Judul</th>
                        <th class="px-5 py-3 text-left">Tipe</th>
                        <th class="px-5 py-3 text-left">Scope</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Respon</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($myReports as $r)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition cursor-pointer"
                        onclick="window.location='{{ route('karyawan.reports.show', $r->id) }}'">
                        <td class="px-5 py-3 font-medium text-slate-900 dark:text-white">{{ Str::limit($r->title, 40) }}</td>
                        <td class="px-5 py-3">
                            @php $ti = ['kebutuhan'=>'📋','kendala'=>'⚠️','saran'=>'💡','progress'=>'📈','lainnya'=>'📄']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#f5e6b3] text-[#8a6d1d]">
                                {{ $ti[$r->type] ?? '📄' }} {{ ucfirst($r->type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $r->scope === 'company' ? '🏛️ Perusahaan' : '🏢 Divisi' }}</td>
                        <td class="px-5 py-3">
                            @php $sc = match($r->status) { 'ready' => 'bg-green-100 text-green-700', 'generated' => 'bg-amber-100 text-amber-700', default => 'bg-slate-100 text-slate-600' }; @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sc }}">{{ ucfirst($r->status) }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @if($r->responses_count > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">💬 {{ $r->responses_count }}</span>
                            @else
                            <span class="text-[10px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $r->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($myReports->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">{{ $myReports->appends(['tab' => 'my'])->links() }}</div>
            @endif
            @else
            <div class="px-6 py-16 text-center">
                <div class="text-4xl mb-3">✍️</div>
                <p class="text-slate-500 font-medium">Anda belum membuat laporan</p>
                <button @click="showCreateModal = true" class="mt-3 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-500 text-white hover:bg-blue-600 transition">Buat Laporan Pertama</button>
            </div>
            @endif
        </div>
    </div>

    {{-- ==================== CREATE REPORT MODAL ==================== --}}
    @include('karyawan.reports.modals.create')

</div>
@endsection
