<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportResponse;
use App\Models\Activity;
use App\Models\Division;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * ===============================
     * DASHBOARD & LIST REPORT
     * ===============================
     */
    public function index(Request $request)
    {
        $totalReports = Report::count();

        $generatedThisMonth = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $companyReports = Report::ofCompany()->count();
        $divisionReports = Report::ofDivision()->count();
        $pendingReports = Report::where('status', 'generated')->count();

        // Filter by scope
        $query = Report::with(['creator', 'division', 'responses']);

        if ($request->filled('scope')) {
            $query->where('scope', $request->scope);
        }
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $reports = $query->latest()->paginate(10);
        $divisions = Division::orderBy('nama_divisi')->get();

        return view('admin.reports.index', compact(
            'totalReports',
            'generatedThisMonth',
            'companyReports',
            'divisionReports',
            'pendingReports',
            'reports',
            'divisions'
        ));
    }

    /**
     * ===============================
     * SHOW REPORT (JSON for popup)
     * ===============================
     */
    public function show(Report $report)
    {
        $report->load(['creator', 'division', 'responses.user']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id'          => $report->id,
                'title'       => $report->title,
                'type'        => $report->type,
                'scope'       => $report->scope,
                'division'    => optional($report->division)->nama_divisi,
                'priority'    => $report->priority,
                'description' => $report->description,
                'status'      => $report->status,
                'start_date'  => $report->start_date
                    ? \Carbon\Carbon::parse($report->start_date)->format('d M Y')
                    : '-',
                'end_date'    => $report->end_date
                    ? \Carbon\Carbon::parse($report->end_date)->format('d M Y')
                    : null,
                'creator'     => optional($report->creator)->name ?? 'System',
                'created_at'  => $report->created_at->format('d M Y, H:i'),
                'responses'   => $report->responses->map(fn($r) => [
                    'id'         => $r->id,
                    'user'       => $r->user->name ?? 'Unknown',
                    'message'    => $r->message,
                    'type'       => $r->type,
                    'created_at' => $r->created_at->format('d M Y, H:i'),
                ]),
            ]);
        }

        // Fallback to page view
        return view('admin.reports.show', compact('report'));
    }

    /**
     * ===============================
     * STORE REPORT (direct save)
     * ===============================
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|string',
            'scope'       => 'required|in:company,division',
            'division_id' => 'nullable|required_if:scope,division|exists:divisions,id',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'summary'     => 'nullable|string',
            'analysis'    => 'nullable|string',
            'report_date' => 'required|date',
        ]);

        $description = trim(
            ($data['summary'] ?? '') . "\n\n" . ($data['analysis'] ?? '')
        );

        $report = Report::create([
            'title'       => $data['title'],
            'type'        => $data['type'],
            'scope'       => $data['scope'],
            'division_id' => $data['scope'] === 'division' ? ($data['division_id'] ?? null) : null,
            'priority'    => $data['priority'] ?? 'normal',
            'description' => $description ?: null,
            'status'      => 'ready',
            'start_date'  => $data['report_date'],
            'created_by'  => auth()->id(),
        ]);

        // LOG AKTIVITAS
        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Membuat laporan: ' . $report->title . ' (' . $data['scope'] . ')',
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('admin.reports')
            ->with('success', 'Laporan berhasil dibuat dan disimpan!');
    }

    /**
     * ===============================
     * ARCHIVE
     * ===============================
     */
    public function archive()
    {
        $reports = Report::with('creator')->latest()->paginate(20);
        return view('admin.reports.archive', compact('reports'));
    }

    /**
     * ===============================
     * DELETE REPORT
     * ===============================
     */
    public function destroy(Report $report)
    {
        $title = $report->title;

        // Delete related responses first
        $report->responses()->delete();
        $report->delete();

        Activity::create([
            'user_id'   => auth()->id(),
            'tanggal'   => now(),
            'deskripsi' => 'Menghapus laporan: ' . $title,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('admin.reports')
            ->with('success', 'Laporan "' . $title . '" berhasil dihapus!');
    }
}
