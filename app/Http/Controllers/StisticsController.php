<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class StisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $monthlyStats = Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, type, SUM(amount) as total')
            ->groupBy('month', 'type')->get();

        $categoryStats = Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->get();

        return view('statistics.index', ['monthlyStats' => $monthlyStats, 'categoryStats' => $categoryStats]);
    }
}
