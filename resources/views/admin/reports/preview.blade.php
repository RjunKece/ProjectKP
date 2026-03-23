@extends('layouts.admin')

@section('title', 'Preview Report')

@section('content')

<div class="max-w-4xl mx-auto space-y-8">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl p-8 border border-[#f5e6b3]">
        <h1 class="text-3xl font-bold text-slate-900">
            {{ $data['title'] }}
        </h1>

        <p class="text-slate-500 mt-2">
            {{ ucfirst($data['type']) }} Report
        </p>
    </div>

    {{-- INFO --}}
    <div class="grid grid-cols-2 gap-6">
        <div class="card">
            <p class="label">Nama Pelapor</p>
            <p>{{ $data['first_name'] ?? '-' }} {{ $data['last_name'] ?? '-' }}</p>
        </div>

        <div class="card">
            <p class="label">Departemen</p>
            <p>{{ $data['department'] ?? '-' }}</p>
        </div>

        <div class="card">
            <p class="label">Tanggal Laporan</p>
            <p>{{ $data['report_date'] ?? '-' }}</p>
        </div>

        <div class="card">
            <p class="label">Status</p>
            <p class="font-semibold text-green-600">
                {{ $data['status'] ?? 'Draft' }}
            </p>
        </div>
    </div>

    {{-- AKTIVITAS --}}
    <div class="card">
        <h3 class="section-title">📌 Ringkasan Aktivitas</h3>
        <p class="text-slate-700 whitespace-pre-line">
            {{ $data['summary'] ?? '-' }}
        </p>
    </div>

    {{-- ANALISIS --}}
    <div class="card">
        <h3 class="section-title">🧠 Analisis & Catatan</h3>
        <p class="text-slate-700 whitespace-pre-line">
            {{ $data['analysis'] ?? '-' }}
        </p>
    </div>

    {{-- ACTION --}}
    <div class="flex justify-end gap-4 pt-4">
        <a href="{{ url()->previous() }}" class="btn-secondary">
            Back
        </a>

<form method="POST" action="{{ route('admin.reports.store') }}">
    @csrf

    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <div class="flex justify-end gap-4 mt-10">
        <a href="{{ url()->previous() }}" class="btn-outline">
            Back
        </a>

        <button type="submit" class="btn-primary">
            Save Report
        </button>
    </div>
</form>

    </div>

</div>
@if(!empty($data['attachment']))
<div class="rounded-xl border border-[#f5e6b3] p-4 mt-6">
    <h4 class="font-semibold mb-3">📎 Lampiran</h4>

    @if(Str::startsWith($data['attachment'], 'temp-reports'))
        <img
            src="{{ asset('storage/'.$data['attachment']) }}"
            class="max-w-full rounded-lg border"
        >
    @else
        <a href="{{ asset('storage/'.$data['attachment']) }}" target="_blank">
            Lihat Lampiran
        </a>
    @endif
</div>
@endif

<style>
.card{
    background:#fff;
    border:1px solid #f5e6b3;
    border-radius:1rem;
    padding:1.5rem;
}
.label{
    font-size:.75rem;
    color:#6b7280;
}
.section-title{
    font-weight:600;
    margin-bottom:.75rem;
}
.btn-primary{
    padding:.6rem 1.4rem;
    border-radius:.75rem;
    font-weight:600;
    background:linear-gradient(135deg,#f5e6b3,#d4af37);
}
.btn-secondary{
    padding:.6rem 1.4rem;
    border-radius:.75rem;
    border:1px solid #e5e7eb;
}
</style>

@endsection
