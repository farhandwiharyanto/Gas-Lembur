<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use Illuminate\Support\Facades\DB;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        $userBagian = auth()->user()->bagian;

        // Calculate totals for employees in the same bagian (Rounded)
        $totalApproved = round(Overtime::where('bagian', $userBagian)->where('status', 'approved')->sum('total_jam'));
        $totalWaiting = round(Overtime::where('bagian', $userBagian)->whereIn('status', ['waiting', 'pending'])->sum('total_jam'));
        $totalRejected = round(Overtime::where('bagian', $userBagian)->where('status', 'rejected')->sum('total_jam'));

        // Top 10 Karyawan Lembuur (Based on SUM of total_jam)
        $top10 = Overtime::select('employee_name', DB::raw('SUM(total_jam) as total_jam'))
            ->where('bagian', $userBagian)
            ->where('status', 'approved')
            ->groupBy('employee_name')
            ->orderByDesc('total_jam')
            ->take(10)
            ->get();
            
        $labels = $top10->map(fn($o) => $o->employee_name)->toArray();
        $data = $top10->map(fn($o) => round($o->total_jam))->toArray();

        return view('pimpinan.dashboard', compact('labels', 'data', 'totalApproved', 'totalWaiting', 'totalRejected', 'top10'));
    }

    public function approvals()
    {
        $userBagian = auth()->user()->bagian;
        
        $overtimes = Overtime::with('user')
                        ->where('bagian', $userBagian) // Harus bagian yang sama
                        ->where('status', 'waiting')
                        ->latest()
                        ->paginate(20);
                        
        return view('pimpinan.approvals', compact('overtimes'));
    }

    public function history()
    {
        $userBagian = auth()->user()->bagian;
        
        $overtimes = Overtime::with('user')
                        ->where('bagian', $userBagian) 
                        ->where('status', '!=', 'waiting')
                        ->latest()
                        ->paginate(20);
        return view('pimpinan.history', compact('overtimes'));
    }

    public function approve($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Pengajuan lembur berhasil disetujui.');
    }

    public function reject($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Pengajuan lembur berhasil ditolak.');
    }
}
