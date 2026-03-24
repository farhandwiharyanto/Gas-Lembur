<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Overtime;

class UserHistoryController extends Controller
{
    public function index()
    {
        // Get the overtimes for the currently logged in user
        $overtimes = Overtime::where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.history.index', compact('overtimes'));
    }
}
