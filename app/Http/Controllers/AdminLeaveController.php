<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;

class AdminLeaveController extends Controller
{
    /**
     * Display a master list of all employee leave applications.
     */
    public function index()
    {
        $leaves = Leave::orderBy('created_at', 'desc')->get();
        return view('admin.cuti.index', compact('leaves'));
    }

    /**
     * Approve a leave request.
     */
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} disetujui.");
    }

    /**
     * Reject a leave request.
     */
    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected']);
        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} ditolak.");
    }

    /**
     * Force approve (bypass) a leave request.
     */
    public function forceApprove($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved']);
        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} berhasil di-bypass (Force Approve).");
    }

    /**
     * Delete a leave request.
     */
    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();
        return redirect()->back()->with('success', "Pengajuan cuti {$leave->employee_name} berhasil dihapus.");
    }
}
