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

    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = Overtime::where('user_id', $userId);

        if ($request->has('month') && !empty($request->month)) {
            $parts = explode('-', $request->month);
            if (count($parts) == 2) {
                $query->whereYear('tanggal_masuk', $parts[0])
                      ->whereMonth('tanggal_masuk', $parts[1]);
            }
        }

        // Calculate totals (Rounded) based on filtered state
        $totalApproved = round((clone $query)->where('status', 'approved')->sum('total_jam'));
        $totalWaiting = round((clone $query)->whereIn('status', ['waiting', 'pending'])->sum('total_jam'));
        $totalRejected = round((clone $query)->where('status', 'rejected')->sum('total_jam'));

        // Get the overtimes for the currently logged in user
        $overtimes = $query->orderBy('tanggal_masuk', 'asc')->paginate(10)->withQueryString();
        $selectedMonth = $request->month ?? '';

        return view('user.history.index', compact('overtimes', 'totalApproved', 'totalWaiting', 'totalRejected', 'selectedMonth'));
    }

    public function bulkDownload(Request $request)
    {
        $userId = auth()->id();
        $overtimes = Overtime::where('user_id', $userId)
            ->whereIn('id', $request->ids ?? [])
            ->whereIn('status', ['approved', 'waiting', 'pending'])
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        if ($overtimes->isEmpty()) {
            return back()->with('error', 'Tidak ada data lembur yang disetujui untuk diunduh.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.bulk-overtime', compact('overtimes'));
        
        $firstOvertime = $overtimes->first();
        $bulanTahun = \Carbon\Carbon::parse($firstOvertime->tanggal_masuk)->translatedFormat('F Y');
        $fileName = "{$firstOvertime->employee_name}_{$firstOvertime->divisi}_MTH_Lemburan {$bulanTahun}.pdf";

        return $pdf->download($fileName);
    }
}
