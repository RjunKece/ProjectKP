<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * ===============================
     * DASHBOARD & LIST REPORT
     * ===============================
     */
    public function index()
    {
        $totalReports = Report::count();

        $generatedThisMonth = Report::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $scheduledReports = Report::where('status', 'scheduled')->count();

        $systemCoverage = 92;
        $storageUsed = '1.2 GB';

        $reports = Report::latest()->paginate(10);

        return view('admin.reports.index', compact(
            'totalReports',
            'generatedThisMonth',
            'scheduledReports',
            'systemCoverage',
            'storageUsed',
            'reports'
        ));
    }

    /**
     * ===============================
     * SHOW / PREVIEW REPORT (VIEW)
     * ===============================
     */
public function show(Report $report)
{
    return view('admin.reports.show-simple', [
        'report' => $report
    ]);
}



    /**
     * ===============================
     * GENERATE REPORT
     * ===============================
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|string',
            'analysis'    => 'nullable|string',
            'summary'     => 'nullable|string',
            'report_date' => 'required|date',
            'attachment'  => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] =
                $request->file('attachment')->store('temp-reports', 'public');
        }

        return view('admin.reports.confirm', compact('data'));
    }

    /**
     * ===============================
     * STORE REPORT
     * ===============================
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|string',
            'analysis'    => 'nullable|string',
            'summary'     => 'nullable|string',
            'report_date' => 'required|date',
            'attachment_path' => 'nullable|string',
        ]);

        $attachment = null;

        if (!empty($data['attachment_path'])) {
            $finalPath = str_replace('temp-reports', 'reports', $data['attachment_path']);
            Storage::disk('public')->move($data['attachment_path'], $finalPath);
            $attachment = $finalPath;
        }

        Report::create([
            'title'       => $data['title'],
            'type'        => $data['type'],
            'description' => $data['analysis'] ?? $data['summary'],
            'status'      => 'ready',
            'start_date'  => $data['report_date'],
            'attachment'  => $attachment,
            'created_by'  => auth()->id(),
        ]);

        return redirect()
            ->route('admin.reports')
            ->with('success', 'Report berhasil disimpan');
    }

    /**
     * ===============================
     * ARCHIVE
     * ===============================
     */
    public function archive()
    {
        $reports = Report::latest()->paginate(20);
        return view('admin.reports.archive', compact('reports'));
    }
}
