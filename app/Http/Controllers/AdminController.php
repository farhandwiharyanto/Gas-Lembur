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
        
        // Daftar 5 user paling sering lembur
        $topUsers = Overtime::select('nama', 'bagian', DB::raw('count(*) as total'))
            ->groupBy('nama', 'bagian')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('admin.index', compact('totalUsers', 'totalApproved', 'totalPending', 'chartLabels', 'chartData', 'topUsers'));
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
}
