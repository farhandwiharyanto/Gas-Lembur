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

        // Cuti (Leave) Stats for this department (bagian)
        $totalCutiApproved = \App\Models\Leave::where('bagian', $userBagian)->where('status', 'approved')->sum('total_hari');
        $totalCutiPending = \App\Models\Leave::where('bagian', $userBagian)->where('status', 'pending')->sum('total_hari');

        // Top 10 Karyawan Cuti (Based on SUM of total_hari)
        $top10Cuti = \App\Models\Leave::select('employee_name', DB::raw('SUM(total_hari) as total_hari'))
            ->where('bagian', $userBagian)
            ->where('status', 'approved')
            ->groupBy('employee_name')
            ->orderByDesc('total_hari')
            ->take(10)
            ->get();

        $cutiLabels = $top10Cuti->map(fn($l) => $l->employee_name)->toArray();
        $cutiData = $top10Cuti->map(fn($l) => round($l->total_hari))->toArray();

        return view('pimpinan.dashboard', compact(
            'labels', 
            'data', 
            'totalApproved', 
            'totalWaiting', 
            'totalRejected', 
            'top10',
            'totalCutiApproved',
            'totalCutiPending',
            'top10Cuti',
            'cutiLabels',
            'cutiData'
        ));
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

    /**
     * Approve multiple overtime requests at once.
     */
    public function bulkApprove(Request $request)
    {
        $userBagian = auth()->user()->bagian;
        $query = Overtime::where('bagian', $userBagian)->where('status', 'waiting');

        if ($request->input('all_selected') === '1') {
            // Approve all waiting overtimes in this department
            $count = $query->update(['status' => 'approved']);
        } else {
            // Approve selected IDs only
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'Tidak ada pengajuan lembur yang terpilih.');
            }
            $count = $query->whereIn('id', $ids)->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', "{$count} pengajuan lembur berhasil disetujui secara masal.");
    }
}
