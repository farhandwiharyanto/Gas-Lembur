<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = Overtime::distinct('user_id')->count('user_id');
        $totalApproved = Overtime::where('status', 'approved')->count();
        $totalPending = Overtime::where('status', 'pending')->count();
        
        // Data for Bar Chart: Jumlah Lembur per Bagian
        $chartQuery = Overtime::select('bagian', DB::raw('count(*) as total'))
            ->groupBy('bagian')
            ->pluck('total', 'bagian');
            
        $chartLabels = $chartQuery->keys()->all();
        $chartData = $chartQuery->values()->all();
        
        // 1. Top 5 Orang Lembur (Semua Status)
        $top5All = Overtime::select('employee_name', DB::raw('SUM(total_jam) as total_jam'))
            ->groupBy('employee_name')
            ->orderByDesc('total_jam')
            ->take(5)
            ->get();

        // 2. Top 5 Approved
        $top5Approved = Overtime::select('employee_name', DB::raw('SUM(total_jam) as total_jam'))
            ->where('status', 'approved')
            ->groupBy('employee_name')
            ->orderByDesc('total_jam')
            ->take(5)
            ->get();

        // 3. Top 5 Pending
        $top5Pending = Overtime::select('employee_name', DB::raw('SUM(total_jam) as total_jam'))
            ->where('status', 'pending')
            ->groupBy('employee_name')
            ->orderByDesc('total_jam')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'totalUsers', 
            'totalApproved', 
            'totalPending', 
            'chartLabels', 
            'chartData', 
            'top5All', 
            'top5Approved', 
            'top5Pending'
        ));
    }

    public function overtimes()
    {
        $overtimes = Overtime::with('user')->latest()->get();
        return view('admin.overtimes.index', compact('overtimes'));
    }

    public function approve($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Pengajuan lembur disetujui.');
    }

    public function reject($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Pengajuan lembur ditolak.');
    }

    public function forceApprove($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update([
            'status' => 'approved',
            // Pilihan opsional jika kita butuh field penanda "di-bypass admin", namun untuk
            // spesifikasi skrg, mengganti status -> approved sudah cukup
        ]);
        return redirect()->back()->with('success', 'Pengajuan lembur berhasil di-bypass (Force Approve).');
    }

    public function destroyOvertime($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();
        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dihapus.');
    }

    public function bulkDownload(Request $request)
    {
        $overtimes = Overtime::whereIn('id', $request->ids ?? [])
            ->where('status', 'approved')
            ->latest()
            ->get();

        if ($overtimes->isEmpty()) {
            return back()->with('error', 'Tidak ada data lembur yang disetujui untuk diunduh.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bulk-overtime', compact('overtimes'));
        return $pdf->download('rekap_lembur_admin_' . date('Ymd_His') . '.pdf');
    }
}
