@extends('layouts.admin')

@section('content')
<div style="padding:20px;background:#fff;border-radius:8px">
    <h2>Preview Report</h2>

    <p><b>Judul:</b> {{ $report->title }}</p>
    <p><b>Tipe:</b> {{ $report->type }}</p>
    <p><b>Status:</b> {{ $report->status }}</p>
    <p><b>Tanggal:</b> {{ $report->start_date }}</p>

    <hr>

    <p>{{ $report->description }}</p>

    <a href="{{ route('admin.reports') }}">← Kembali</a>
</div>
@endsection
