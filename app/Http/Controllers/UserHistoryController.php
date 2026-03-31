<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;

class UserHistoryController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        
        // Stats (Rounded)
        $totalApproved = round(Overtime::where('user_id', $userId)->where('status', 'approved')->sum('total_jam'));
        $totalWaiting = round(Overtime::where('user_id', $userId)->whereIn('status', ['waiting', 'pending'])->sum('total_jam'));
        $totalRejected = round(Overtime::where('user_id', $userId)->where('status', 'rejected')->sum('total_jam'));

        // Chart Data (Last 6 months)
        $chartData = Overtime::where('user_id', $userId)
            ->where('status', 'approved')
            ->selectRaw("TO_CHAR(created_at, 'Mon YYYY') as month, SUM(total_jam) as total")
            ->groupBy('month')
            ->orderByRaw("MIN(created_at) ASC")
            ->get();

        $labels = $chartData->pluck('month')->toArray();
        $data = $chartData->map(fn($item) => round($item->total))->toArray();

        return view('user.dashboard', compact('totalApproved', 'totalWaiting', 'totalRejected', 'labels', 'data'));
    }

    public function index()
    {
        $userId = auth()->id();
        // Calculate totals (Rounded)
        $totalApproved = round(Overtime::where('user_id', $userId)->where('status', 'approved')->sum('total_jam'));
        $totalWaiting = round(Overtime::where('user_id', $userId)->whereIn('status', ['waiting', 'pending'])->sum('total_jam'));
        $totalRejected = round(Overtime::where('user_id', $userId)->where('status', 'rejected')->sum('total_jam'));

        // Get the overtimes for the currently logged in user
        $overtimes = Overtime::where('user_id', $userId)->latest()->paginate(10);
        return view('user.history.index', compact('overtimes', 'totalApproved', 'totalWaiting', 'totalRejected'));
    }

    public function bulkDownload(Request $request)
    {
        $userId = auth()->id();
        $overtimes = Overtime::where('user_id', $userId)
            ->whereIn('id', $request->ids ?? [])
            ->where('status', 'approved')
            ->latest()
            ->get();

        if ($overtimes->isEmpty()) {
            return back()->with('error', 'Tidak ada data lembur yang disetujui untuk diunduh.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bulk-overtime', compact('overtimes'));
        return $pdf->download('rekap_lembur_gabungan_' . date('Ymd_His') . '.pdf');
    }
}
