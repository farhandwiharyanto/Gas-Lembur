<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function export()
    {
        $fileName = 'rekap_lembur_' . date('Y-m-d_H-i-s') . '.csv';
        $overtimes = Overtime::with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Employee Name', 'Employee No', 'Sub Bagian', 'Bagian', 'Divisi', 'Direktorat', 'Status'];

        $callback = function() use($overtimes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($overtimes as $ot) {
                $row['Tanggal']  = $ot->created_at->format('Y-m-d H:i:s');
                $row['Name']     = $ot->employee_name;
                $row['No']       = $ot->employee_no;
                $row['SubBag']   = $ot->sub_bagian;
                $row['Bagian']   = $ot->bagian;
                $row['Divisi']   = $ot->divisi;
                $row['Dir']      = $ot->direktorat;
                $row['Status']   = $ot->status;

                fputcsv($file, array($row['Tanggal'], $row['Name'], $row['No'], $row['SubBag'], $row['Bagian'], $row['Divisi'], $row['Dir'], $row['Status']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
