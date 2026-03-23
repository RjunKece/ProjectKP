@extends('layouts.admin')

@section('title', 'Confirm Report')

@section('content')
<div class="max-w-3xl mx-auto mt-16">

    <div class="erp-card p-10 text-center space-y-6">

        <div class="text-4xl">⚠️</div>

        <h1 class="text-2xl font-semibold text-slate-900">
            Simpan Laporan?
        </h1>

        <p class="text-slate-500 max-w-xl mx-auto">
            Laporan <strong>{{ $data['title'] ?? '-' }}</strong>
            akan disimpan ke sistem ERP dan tidak bisa diubah tanpa proses edit.
        </p>

        <div class="flex justify-center gap-4 pt-6">

            {{-- NANTI DULU --}}
            <a href="{{ route('admin.reports') }}"
               class="px-6 py-2 rounded-xl border border-slate-300">
                Nanti Dulu
            </a>

            {{-- LANJUT PREVIEW --}}
<form method="POST" action="{{ route('admin.reports.preview') }}">
    @csrf

    @foreach ($data as $key => $value)
        @if (!is_array($value))
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <button
        class="px-6 py-2 rounded-xl font-semibold
               bg-gradient-to-r from-[#f5e6b3] to-[#d4af37]">
        Ya, Lanjut Preview
    </button>
</form>


        </div>
    </div>

</div>
@endsection
