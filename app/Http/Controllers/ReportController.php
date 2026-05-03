<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;

class ReportController extends Controller
{
    public function index()
    {
        $years = Overtime::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return view('admin.reports.index', compact('years'));
    }

    public function export(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $query = Overtime::with('user')->orderBy('tanggal_masuk', 'asc');

        if ($month) {
            $query->whereMonth('tanggal_masuk', $month);
        }
        if ($year) {
            $query->whereYear('tanggal_masuk', $year);
        }

        $overtimes = $query->get();

        if ($overtimes->isEmpty()) {
            return back()->with('error', 'Tidak ada data lembur untuk periode ini.');
        }

        $monthName = $month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') : null;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report-recap', compact('overtimes', 'monthName', 'year'));
        $pdf->setPaper('a4', 'landscape');

        $fileName = 'rekap_lembur_' . ($month ? "bln_{$month}_" : "") . ($year ? "thn_{$year}_" : "") . date('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
