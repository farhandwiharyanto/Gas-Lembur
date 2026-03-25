<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;

class UserHistoryController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        // Calculate totals
        $totalApproved = Overtime::where('user_id', $userId)->where('status', 'approved')->sum('total_jam');
        $totalWaiting = Overtime::where('user_id', $userId)->whereIn('status', ['waiting', 'pending'])->sum('total_jam');
        $totalRejected = Overtime::where('user_id', $userId)->where('status', 'rejected')->sum('total_jam');

        // Get the overtimes for the currently logged in user
        $overtimes = Overtime::where('user_id', $userId)->latest()->paginate(10);
        return view('user.history.index', compact('overtimes', 'totalApproved', 'totalWaiting', 'totalRejected'));
    }
}
