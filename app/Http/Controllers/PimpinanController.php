<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use Illuminate\Support\Facades\DB;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        // Chart: User with highest count of approved overtimes
        $chartData = Overtime::select('user_id', DB::raw('count(*) as total'))
            ->where('status', 'approved')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(10)
            ->with('user:id,name')
            ->get();
            
        $labels = $chartData->map(fn($o) => $o->user->name ?? 'Unknown')->toArray();
        $data = $chartData->map(fn($o) => $o->total)->toArray();

        return view('pimpinan.dashboard', compact('labels', 'data'));
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
