<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;
use Carbon\Carbon;

class PerhitunganLemburController extends Controller
{
    /**
     * Display the calculations page synchronized with actual overtime database records.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $gajiPokok = 5880000;
        $rateLembur = MathRound($gajiPokok / 173, 2); // Rp 33.988,44
        
        $query = Overtime::where('user_id', $userId);

        // Apply Month & Year filter if provided
        if ($request->has('month') && !empty($request->month)) {
            $parts = explode('-', $request->month);
            if (count($parts) == 2) {
                $query->whereYear('tanggal_masuk', $parts[0])
                      ->whereMonth('tanggal_masuk', $parts[1]);
            }
        }

        // Get overtimes for the user
        $overtimes = $query->orderBy('tanggal_masuk', 'asc')->get();
        $selectedMonth = $request->month ?? '';

        // Calculate custom calculations for each overtime record
        $totalApprovedUang = 0;
        $totalWaitingUang = 0;
        $totalApprovedMultiplier = 0;
        $totalWaitingMultiplier = 0;

        foreach ($overtimes as $overtime) {
            $kotor = (float) $overtime->total_jam;
            
            // 1. Calculate break hour deduction
            $potongan = 0;
            if ($kotor >= 20) {
                $potongan = 4;
            } else if ($kotor >= 15) {
                $potongan = 3;
            } else if ($kotor >= 10) {
                $potongan = 2;
            } else if ($kotor >= 5) {
                $potongan = 1;
            }
            
            $efektif = max(0.0, $kotor - $potongan);
            
            // 2. Identify weekend
            $date = Carbon::parse($overtime->tanggal_masuk);
            $isWeekend = ($date->dayOfWeek === Carbon::SATURDAY || $date->dayOfWeek === Carbon::SUNDAY);
            
            // 3. Calculate multiplier
            $multiplier = 0.0;
            if ($efektif > 0) {
                if (!$isWeekend) {
                    // Weekday: 1.5x first hour, 2.0x thereafter
                    if ($efektif <= 1) {
                        $multiplier = $efektif * 1.5;
                    } else {
                        $multiplier = 1.5 + ($efektif - 1) * 2;
                    }
                } else {
                    // Weekend: <=8 hours @ 2x, exactly 9 hours @ 19x, >9 hours @ 19 + (efektif - 9) * 4
                    if ($efektif <= 8) {
                        $multiplier = $efektif * 2;
                    } else if ($efektif > 8 && $efektif <= 9) {
                        $multiplier = 16 + ($efektif - 8) * 3;
                    } else {
                        $multiplier = 19 + ($efektif - 9) * 4;
                    }
                }
            }

            // 4. Calculate total pay for this record
            $nominal = round($multiplier * $rateLembur);

            // Set temporary attributes
            $overtime->potongan_jam = $potongan;
            $overtime->jam_efektif = $efektif;
            $overtime->is_weekend = $isWeekend;
            $overtime->multiplier = $multiplier;
            $overtime->nominal_lembur = $nominal;

            // Aggregation based on status
            if ($overtime->status === 'approved') {
                $totalApprovedUang += $nominal;
                $totalApprovedMultiplier += $multiplier;
            } elseif (in_array($overtime->status, ['waiting', 'pending'])) {
                $totalWaitingUang += $nominal;
                $totalWaitingMultiplier += $multiplier;
            }
        }

        return view('user.perhitungan.index', compact(
            'overtimes',
            'selectedMonth',
            'gajiPokok',
            'rateLembur',
            'totalApprovedUang',
            'totalWaitingUang',
            'totalApprovedMultiplier',
            'totalWaitingMultiplier'
        ));
    }
}

/**
 * Helper to round floats to custom decimal places.
 */
function MathRound($value, $precision = 2) {
    return round($value, $precision);
}
