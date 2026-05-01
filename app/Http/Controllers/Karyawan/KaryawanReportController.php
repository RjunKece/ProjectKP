<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Report;
use App\Models\ReportResponse;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanReportController extends Controller
{
    /**
     * ===============================
     * INDEX - Dua bagian: Perusahaan & Divisi
     * ===============================
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $user->load(['role', 'division']);

        $tab = $request->get('tab', 'company');

        // ===== LAPORAN PERUSAHAAN (pengumuman, info penting dari admin) =====
        $companyReports = Report::ofCompany()
            ->where('status', 'ready')
            ->with(['creator', 'responses' => fn($q) => $q->where('user_id', $user->id)])
            ->withCount('responses')
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->latest()
            ->paginate(10, ['*'], 'company_page');

        // ===== LAPORAN DIVISI (tugas divisi, laporan dari admin ke divisi user) =====
        $divisionReports = collect();
        $myDivisionReports = collect();
        if ($user->division_id) {
            $divisionReports = Report::ofDivision($user->division_id)
                ->where('status', 'ready')
                ->with(['creator', 'responses' => fn($q) => $q->where('user_id', $user->id)])
                ->withCount('responses')
                ->latest()
                ->paginate(10, ['*'], 'division_page');
        }

        // ===== LAPORAN BUATAN KARYAWAN =====
        $myReports = Report::where('created_by', $user->id)
            ->with(['division', 'responses'])
            ->withCount('responses')
            ->latest()
            ->paginate(10, ['*'], 'my_page');

        // ===== KPI =====
        $totalCompany = Report::ofCompany()->where('status', 'ready')->count();
        $totalDivision = $user->division_id
            ? Report::ofDivision($user->division_id)->where('status', 'ready')->count()
            : 0;
        $totalMyReports = Report::where('created_by', $user->id)->count();
        $unreadCompany = Report::ofCompany()
            ->where('status', 'ready')
            ->whereDoesntHave('responses', fn($q) => $q->where('user_id', $user->id))
            ->count();

        $divisions = Division::orderBy('nama_divisi')->get();

        return view('karyawan.reports.index', compact(
            'tab',
            'companyReports',
            'divisionReports',
            'myReports',
            'totalCompany',
            'totalDivision',
            'totalMyReports',
            'unreadCompany',
            'divisions'
        ));
    }

    /**
     * ===============================
     * SHOW REPORT DETAIL
     * ===============================
     */
    public function show(Report $report)
    {
        $user = Auth::user();

        // Karyawan can see: company reports, their division reports, or their own reports
        $canView = ($report->scope === 'company' && $report->status === 'ready')
            || ($report->scope === 'division' && $report->division_id === $user->division_id && $report->status === 'ready')
            || ($report->created_by === $user->id);

        if (!$canView) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $report->load(['creator', 'division', 'responses.user']);

        // Auto-acknowledge jika belum
        $alreadyAcked = $report->responses()
            ->where('user_id', $user->id)
            ->where('type', 'acknowledgment')
            ->exists();

        if (!$alreadyAcked && $report->created_by !== $user->id) {
            ReportResponse::create([
                'report_id' => $report->id,
                'user_id'   => $user->id,
                'message'   => 'Sudah dibaca',
                'type'      => 'acknowledgment',
            ]);
            $report->load('responses.user'); // reload
        }

        return view('karyawan.reports.show', compact('report'));
    }

    /**
     * ===============================
     * STORE - Karyawan buat laporan
     * ===============================
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'type'        => 'required|string|in:kebutuhan,kendala,saran,progress,lainnya',
            'scope'       => 'required|in:company,division',
            'description' => 'required|string|max:3000',
            'priority'    => 'nullable|in:low,normal,high,urgent',
        ]);

        $report = Report::create([
            'title'       => $data['title'],
            'type'        => $data['type'],
            'scope'       => $data['scope'],
            'division_id' => $data['scope'] === 'division' ? $user->division_id : null,
            'description' => $data['description'],
            'priority'    => $data['priority'] ?? 'normal',
            'status'      => 'generated', // karyawan reports need admin review
            'start_date'  => now(),
            'created_by'  => $user->id,
        ]);

        Activity::create([
            'user_id'   => $user->id,
            'tanggal'   => now(),
            'deskripsi' => 'Membuat laporan: ' . $report->title,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('karyawan.reports', ['tab' => 'my'])
            ->with('success', 'Laporan berhasil dikirim! Admin akan meninjau laporan Anda.');
    }

    /**
     * ===============================
     * REPLY - Karyawan balas laporan
     * ===============================
     */
    public function reply(Request $request, Report $report)
    {
        $user = Auth::user();

        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ReportResponse::create([
            'report_id' => $report->id,
            'user_id'   => $user->id,
            'message'   => $data['message'],
            'type'      => 'reply',
        ]);

        Activity::create([
            'user_id'   => $user->id,
            'tanggal'   => now(),
            'deskripsi' => 'Membalas laporan: ' . $report->title,
            'status'    => 'completed',
        ]);

        return redirect()
            ->route('karyawan.reports.show', $report->id)
            ->with('success', 'Balasan berhasil dikirim!');
    }
}
