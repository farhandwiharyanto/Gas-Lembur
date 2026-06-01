<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;

class PimpinanLeaveController extends Controller
{
    /**
     * Display leave requests awaiting approval.
     */
    public function approvals()
    {
        $bagian = auth()->user()->bagian;
        
        $leaves = Leave::where('status', 'pending')
            ->where('bagian', $bagian)
            ->orderBy('tanggal_mulai', 'asc')
            ->paginate(15);

        return view('pimpinan.cuti.approvals', compact('leaves'));
    }

    /**
     * Display approved leaves history.
     */
    public function history()
    {
        $bagian = auth()->user()->bagian;
        
        $leaves = Leave::where('status', 'approved')
            ->where('bagian', $bagian)
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(15);

        return view('pimpinan.cuti.history', compact('leaves'));
    }

    /**
     * Approve a single leave request.
     */
    public function approve($id)
    {
        $bagian = auth()->user()->bagian;
        
        $leave = Leave::where('id', $id)
            ->where('bagian', $bagian)
            ->where('status', 'pending')
            ->firstOrFail();

        $leave->update(['status' => 'approved']);

        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} berhasil disetujui.");
    }

    /**
     * Reject a single leave request.
     */
    public function reject($id)
    {
        $bagian = auth()->user()->bagian;
        
        $leave = Leave::where('id', $id)
            ->where('bagian', $bagian)
            ->where('status', 'pending')
            ->firstOrFail();

        $leave->update(['status' => 'rejected']);

        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} telah ditolak.");
    }

    /**
     * Bulk approve selected leave requests.
     */
    public function bulkApprove(Request $request)
    {
        $bagian = auth()->user()->bagian;

        $query = Leave::where('status', 'pending')
            ->where('bagian', $bagian);

        if ($request->all_selected == '1') {
            // Approve all pending leaves for this department
            $leavesToApprove = $query->get();
        } else {
            // Approve only selected IDs
            $leavesToApprove = $query->whereIn('id', $request->ids ?? [])->get();
        }

        if ($leavesToApprove->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengajuan cuti yang terpilih.');
        }

        foreach ($leavesToApprove as $leave) {
            $leave->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', count($leavesToApprove) . ' pengajuan cuti berhasil disetujui secara masal.');
    }
}
