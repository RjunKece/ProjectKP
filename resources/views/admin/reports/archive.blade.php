@extends('layouts.admin')

@section('content')
<h2>Report Archive</h2>

<table class="table">
    <tr>
        <th>Judul</th>
        <th>Tipe</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach($reports as $report)
    <tr>
        <td>{{ $report->title }}</td>
        <td>{{ $report->type }}</td>
        <td>{{ ucfirst($report->status) }}</td>
        <td>
            <a href="{{ route('admin.reports.show', $report->id) }}">
                View
            </a>
        </td>
    </tr>
    @endforeach
</table>

{{ $reports->links() }}
@endsection
