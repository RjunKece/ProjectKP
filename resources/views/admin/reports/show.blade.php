@extends('layouts.admin')
@section('title', $report->title)

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('admin.reports') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-[#d4af37] mb-6 transition">
        ← Kembali ke Reports
    </a>

    {{-- Report Card --}}
    <div class="erp-card p-8">

        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $report->title }}</h1>
            <div class="flex justify-center items-center gap-3 mt-3">
                <span class="h-[2px] w-10 bg-[#f5e6b3]"></span>
                <span class="h-[3px] w-16 bg-[#d4af37] rounded-full"></span>
                <span class="h-[2px] w-10 bg-[#f5e6b3]"></span>
            </div>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-sm">
            <div>
                <span class="text-slate-500 dark:text-slate-400 block">Tipe</span>
                <span class="font-medium text-slate-900 dark:text-white capitalize">{{ $report->type }}</span>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 block">Status</span>
                <span class="font-medium capitalize {{ $report->status === 'ready' ? 'text-green-600' : 'text-amber-600' }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 block">Tanggal</span>
                <span class="font-medium text-slate-900 dark:text-white">
                    {{ $report->start_date ? \Carbon\Carbon::parse($report->start_date)->format('d M Y') : '-' }}
                </span>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 block">Creator</span>
                <span class="font-medium text-slate-900 dark:text-white">
                    {{ optional($report->creator)->name ?? 'System' }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
            {{ $report->description ?? 'Tidak ada deskripsi.' }}
        </div>

        {{-- Footer --}}
        <div class="mt-6 text-xs text-slate-400 text-right">
            Dibuat pada: {{ $report->created_at->format('d M Y, H:i') }}
        </div>

    </div>
</div>

@endsection
