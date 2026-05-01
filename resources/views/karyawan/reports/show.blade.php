@extends('layouts.karyawan')
@section('title', 'Detail Laporan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- NOTIFICATIONS --}}
    @if(session('success'))
        <div x-data="{ s: true }" x-show="s" x-init="setTimeout(() => s = false, 4000)" x-transition
             class="px-5 py-3 rounded-xl bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-300 text-sm font-medium flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
            <button @click="s = false" class="text-green-400">✕</button>
        </div>
    @endif

    {{-- BACK --}}
    <a href="{{ route('karyawan.reports') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Laporan
    </a>

    {{-- HEADER --}}
    <div class="erp-card p-6 border-l-4 {{ $report->scope === 'company' ? 'border-l-emerald-500' : 'border-l-blue-500' }}">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $report->title }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ ucfirst($report->type) }} Report</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                @php
                    $prioC = match($report->priority) {
                        'urgent' => 'bg-red-100 text-red-700', 'high' => 'bg-orange-100 text-orange-700',
                        default => 'bg-slate-100 text-slate-600'
                    };
                    $statusC = match($report->status) {
                        'ready' => 'bg-green-100 text-green-700', 'generated' => 'bg-amber-100 text-amber-700',
                        default => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <span class="px-2.5 py-1 text-[10px] rounded-full font-semibold {{ $prioC }}">{{ ucfirst($report->priority) }}</span>
                <span class="px-2.5 py-1 text-[10px] rounded-full font-semibold {{ $statusC }}">{{ ucfirst($report->status) }}</span>
            </div>
        </div>
    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="erp-card p-4">
            <p class="text-[10px] uppercase text-slate-400 font-semibold">Scope</p>
            <p class="font-medium text-sm text-slate-900 dark:text-white mt-1">
                {{ $report->scope === 'company' ? '🏛️ Perusahaan' : '🏢 ' . optional($report->division)->nama_divisi }}
            </p>
        </div>
        <div class="erp-card p-4">
            <p class="text-[10px] uppercase text-slate-400 font-semibold">Dibuat Oleh</p>
            <p class="font-medium text-sm text-slate-900 dark:text-white mt-1">{{ optional($report->creator)->name ?? 'System' }}</p>
        </div>
        <div class="erp-card p-4">
            <p class="text-[10px] uppercase text-slate-400 font-semibold">Periode</p>
            <p class="font-medium text-sm text-slate-900 dark:text-white mt-1">
                {{ $report->start_date ? \Carbon\Carbon::parse($report->start_date)->format('d M Y') : '-' }}
            </p>
        </div>
        <div class="erp-card p-4">
            <p class="text-[10px] uppercase text-slate-400 font-semibold">Tanggal Dibuat</p>
            <p class="font-medium text-sm text-slate-900 dark:text-white mt-1">{{ $report->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    {{-- DESCRIPTION --}}
    @if($report->description)
    <div class="erp-card p-6">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-3">📋 Isi Laporan</h3>
        <div class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed bg-slate-50 dark:bg-slate-800 rounded-xl p-5">{{ $report->description }}</div>
    </div>
    @endif

    {{-- RESPONSES / REPLIES --}}
    <div class="erp-card p-6">
        <h3 class="font-semibold text-slate-900 dark:text-white mb-4">
            💬 Tanggapan & Balasan
            <span class="text-xs font-normal text-slate-500 ml-2">({{ $report->responses->count() }} tanggapan)</span>
        </h3>

        @if($report->responses->count())
        <div class="space-y-3 mb-6">
            @foreach($report->responses->sortBy('created_at') as $res)
            <div class="flex gap-3 {{ $res->type === 'acknowledgment' ? 'opacity-60' : '' }}">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                            {{ $res->user_id === $report->created_by ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ strtoupper(substr(optional($res->user)->name ?? '?', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0 bg-slate-50 dark:bg-slate-800 rounded-xl p-3 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold text-slate-900 dark:text-white">{{ optional($res->user)->name ?? 'Unknown' }}</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-medium {{ $res->type === 'reply' ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                            {{ $res->type === 'reply' ? 'Reply' : '✓ Dibaca' }}
                        </span>
                        <span class="text-[10px] text-slate-400">{{ $res->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $res->message }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-slate-400 mb-6">Belum ada tanggapan.</p>
        @endif

        {{-- REPLY FORM --}}
        @if($report->created_by !== auth()->id() || $report->responses->where('type', 'reply')->where('user_id', auth()->id())->count() === 0)
        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
            <form method="POST" action="{{ route('karyawan.reports.reply', $report->id) }}" class="flex gap-3">
                @csrf
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <textarea name="message" rows="2" required class="input w-full text-sm" placeholder="Tulis balasan atau tanggapan Anda..."></textarea>
                    <button type="submit" class="mt-2 px-4 py-2 rounded-lg text-xs font-semibold bg-blue-500 text-white hover:bg-blue-600 transition">
                        📤 Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection
