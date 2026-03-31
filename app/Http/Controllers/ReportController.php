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

        $query = Overtime::with('user')->orderBy('created_at', 'desc');

        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        $overtimes = $query->get();

        $fileName = 'rekap_lembur_' . ($month ? "bln_{$month}_" : "") . ($year ? "thn_{$year}_" : "") . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Employee Name', 'Employee No', 'Sub Bagian', 'Bagian', 'Divisi', 'Direktorat', 'Total Jam', 'Status'];

        $callback = function() use($overtimes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($overtimes as $ot) {
                fputcsv($file, [
                    $ot->created_at->format('Y-m-d H:i:s'),
                    $ot->employee_name,
                    $ot->employee_no,
                    $ot->sub_bagian,
                    $ot->bagian,
                    $ot->divisi,
                    $ot->direktorat,
                    round($ot->total_jam),
                    $ot->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
