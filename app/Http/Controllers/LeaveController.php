<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display the leave dashboard with remaining/used days statistics and charts.
     */
    public function dashboard()
    {
        $userId = auth()->id();
        
        $usedLeave = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('total_hari');
            
        $sisaCuti = max(0, 12 - $usedLeave);
        
        $recentLeaves = Leave::where('user_id', $userId)
            ->orderBy('tanggal_mulai', 'desc')
            ->take(5)
            ->get();

        return view('user.cuti.dashboard', compact('usedLeave', 'sisaCuti', 'recentLeaves'));
    }

    /**
     * Display leave application history.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = Leave::where('user_id', $userId);
        
        // Filter by month/year if needed
        if ($request->has('month') && !empty($request->month)) {
            $parts = explode('-', $request->month);
            if (count($parts) == 2) {
                $query->whereYear('tanggal_mulai', $parts[0])
                      ->whereMonth('tanggal_mulai', $parts[1]);
            }
        }

        $leaves = $query->orderBy('tanggal_mulai', 'desc')->paginate(10)->withQueryString();
        $selectedMonth = $request->month ?? '';

        return view('user.cuti.index', compact('leaves', 'selectedMonth'));
    }

    /**
     * Show form to apply for new leave.
     */
    public function create()
    {
        return view('user.cuti.create');
    }

    /**
     * Store new leave request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:' . date('Y-m-d', strtotime('-1 year')),
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        $userId = auth()->id();
        $user = auth()->user();

        // Calculate leave days excluding weekends and holidays
        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_selesai);
        $totalDays = 0;

        while ($start->lte($end)) {
            if (!$this->isWeekendOrHoliday($start)) {
                $totalDays++;
            }
            $start->addDay();
        }

        if ($totalDays == 0) {
            return back()->with('error', 'Tanggal pengajuan cuti hanya berisi hari libur/akhir pekan. Cuti tidak diperlukan.')->withInput();
        }

        // Validate sisa cuti limit
        $usedLeave = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('total_hari');
            
        $sisaCuti = max(0, 12 - $usedLeave);

        if ($totalDays > $sisaCuti) {
            return back()->with('error', "Jatah sisa cuti Anda tidak mencukupi. Sisa cuti aktif Anda saat ini: {$sisaCuti} hari. Pengajuan ini membutuhkan: {$totalDays} hari kerja.")->withInput();
        }

        Leave::create([
            'user_id' => $userId,
            'employee_name' => $user->name,
            'employee_no' => $user->nik,
            'bagian' => $user->bagian,
            'divisi' => $user->divisi,
            'direktorat' => $user->direktorat,
            'lokasi_kerja' => $user->lokasi_kerja,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'total_hari' => $totalDays,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        return redirect()->route('user.cuti.index')->with('success', 'Pengajuan cuti berhasil dikirim ke Pimpinan.');
    }

    /**
     * Show form to edit leave.
     */
    public function edit($id)
    {
        $leave = Leave::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($leave->status !== 'pending') {
            return redirect()->route('user.cuti.index')->with('error', 'Hanya pengajuan cuti yang masih menunggu persetujuan yang dapat diedit.');
        }

        return view('user.cuti.create', compact('leave'));
    }

    /**
     * Update leave request.
     */
    public function update(Request $request, $id)
    {
        $leave = Leave::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($leave->status !== 'pending') {
            return redirect()->route('user.cuti.index')->with('error', 'Hanya pengajuan cuti yang masih menunggu persetujuan yang dapat diubah.');
        }

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
        ]);

        $userId = auth()->id();

        // Calculate leave days excluding weekends and holidays
        $start = Carbon::parse($request->tanggal_mulai);
        $end = Carbon::parse($request->tanggal_selesai);
        $totalDays = 0;

        while ($start->lte($end)) {
            if (!$this->isWeekendOrHoliday($start)) {
                $totalDays++;
            }
            $start->addDay();
        }

        if ($totalDays == 0) {
            return back()->with('error', 'Tanggal pengajuan cuti hanya berisi hari libur/akhir pekan.')->withInput();
        }

        // Validate sisa cuti (excluding this leave's previous total)
        $usedLeave = Leave::where('user_id', $userId)
            ->where('id', '!=', $id)
            ->where('status', 'approved')
            ->sum('total_hari');
            
        $sisaCuti = max(0, 12 - $usedLeave);

        if ($totalDays > $sisaCuti) {
            return back()->with('error', "Jatah sisa cuti Anda tidak mencukupi. Sisa jatah cuti aktif: {$sisaCuti} hari. Pengajuan ini membutuhkan: {$totalDays} hari.")->withInput();
        }

        $leave->update([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'total_hari' => $totalDays,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('user.cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Delete leave request.
     */
    public function destroy($id)
    {
        $leave = Leave::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($leave->status !== 'pending') {
            return redirect()->route('user.cuti.index')->with('error', 'Hanya pengajuan cuti yang masih menunggu persetujuan yang dapat dibatalkan.');
        }

        $leave->delete();

        return redirect()->route('user.cuti.index')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    /**
     * Download leave request as PDF.
     */
    public function print($id)
    {
        $leave = Leave::with('user')->findOrFail($id);
        
        // Dynamic Jabatan mapping
        $jabatan = 'Staff';
        if ($leave->user->role === 'pimpinan') {
            $jabatan = 'Pimpinan';
        } elseif ($leave->user->role === 'admin') {
            $jabatan = 'System Administrator';
        } else {
            $jabatan = 'Junior Engineer';
        }

        // Tanggal Masuk
        $tanggalMasuk = 'September 2025';
        if (str_contains(strtolower($leave->employee_name), 'farhan')) {
            $tanggalMasuk = 'Oktober 2022';
        }

        // Keterangan Cuti
        $keteranganCuti = 'Cuti Personal';

        // Sisa Cuti calculation: sisa quota before/after this leave
        $approvedAndThis = Leave::where('user_id', $leave->user_id)
            ->where(function($q) use ($leave) {
                $q->where('status', 'approved')
                  ->orWhere('id', $leave->id);
            })
            ->where('created_at', '<=', $leave->created_at)
            ->sum('total_hari');
        
        $sisaCutiVal = max(0, 12 - $approvedAndThis);

        // Mulai Cuti format: e.g. "25/03/2026 - 27/03/2026"
        $startDate = \Carbon\Carbon::parse($leave->tanggal_mulai);
        $endDate = \Carbon\Carbon::parse($leave->tanggal_selesai);
        if ($startDate->eq($endDate)) {
            $mulaiCutiStr = $startDate->format('d/m/Y');
        } else {
            $mulaiCutiStr = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        }

        // Return to work date (next working day after tanggal_selesai)
        $returnDate = $endDate->copy()->addDay();
        while ($this->isWeekendOrHoliday($returnDate)) {
            $returnDate->addDay();
        }
        $returnDateStr = $returnDate->format('d/m/Y');

        // Pimpinan
        $pimpinan = \App\Models\User::where('role', 'pimpinan')
            ->where('bagian', $leave->bagian)
            ->first();

        $resolvePath = function($path) {
            if (!$path) return null;
            if (str_starts_with($path, '/uploads')) {
                $fullPath = public_path($path);
            } else {
                $fullPath = public_path('storage/' . $path);
            }
            return file_exists($fullPath) ? $fullPath : null;
        };

        $pimpinanPath = $resolvePath($pimpinan->tanda_tangan ?? null);
        $userPath = $resolvePath($leave->user->tanda_tangan ?? null);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.leave-form', compact(
            'leave',
            'jabatan',
            'tanggalMasuk',
            'keteranganCuti',
            'sisaCutiVal',
            'mulaiCutiStr',
            'returnDateStr',
            'pimpinan',
            'pimpinanPath',
            'userPath'
        ));
        $pdf->setPaper('a4', 'portrait');

        $bulanTahun = \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('F Y');
        $fileName = "{$leave->employee_name}_{$leave->divisi}_MTH_Cuti {$bulanTahun}.pdf";
        return $pdf->download($fileName);
    }

    /**
     * Get list of national holidays for 2026.
     */
    private function getHolidays2026()
    {
        return [
            '2026-01-01', // Tahun Baru Masehi
            '2026-01-16', // Isra Mikraj
            '2026-02-17', // Imlek
            '2026-03-18', // Cuti Bersama Nyepi (Libur Perusahaan)
            '2026-03-19', // Nyepi
            '2026-03-20', // Cuti Bersama Idul Fitri (Libur Perusahaan)
            '2026-03-21', // Idul Fitri
            '2026-03-22', // Idul Fitri
            '2026-03-23', // Cuti Bersama Idul Fitri (Libur Perusahaan)
            '2026-03-24', // Cuti Bersama Idul Fitri (Libur Perusahaan)
            '2026-04-03', // Wafat Yesus Kristus
            '2026-04-05', // Paskah
            '2026-05-01', // Hari Buruh
            '2026-05-14', // Kenaikan Yesus Kristus
            '2026-05-27', // Idul Adha
            '2026-05-31', // Waisak
            '2026-06-01', // Hari Lahir Pancasila
            '2026-06-16', // Tahun Baru Islam
            '2026-08-17', // Proklamasi Kemerdekaan
            '2026-08-25', // Maulid Nabi
            '2026-12-24', // Cuti Bersama Natal (Libur Perusahaan)
            '2026-12-25', // Kelahiran Yesus Kristus
        ];
    }

    /**
     * Check if a date is a weekend or a holiday.
     */
    private function isWeekendOrHoliday($date)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        
        // Weekend check (Saturday or Sunday)
        if ($carbonDate->dayOfWeek === \Carbon\Carbon::SATURDAY || $carbonDate->dayOfWeek === \Carbon\Carbon::SUNDAY) {
            return true;
        }
        
        // Holiday check
        $formattedDate = $carbonDate->format('Y-m-d');
        if (in_array($formattedDate, $this->getHolidays2026())) {
            return true;
        }
        
        return false;
    }
}
