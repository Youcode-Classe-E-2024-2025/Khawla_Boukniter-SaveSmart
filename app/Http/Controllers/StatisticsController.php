<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class StatisticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $baseQuery = Transaction::query();

        if ($user->account_type === 'family') {
            $baseQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('family_id', $user->family_id);
            });
        } else {
            $baseQuery->where('user_id', $user->id);
        }

        $monthlyStats = $baseQuery->selectRaw('EXTRACT(MONTH FROM created_at) as month, type, SUM(amount) as total')
            ->groupBy('month', 'type')
            ->get();

        $categoryStats = Transaction::query()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where(function ($q) use ($user) {
                $q->where('transactions.user_id', $user->id)
                    ->orWhere('transactions.family_id', $user->family_id);
            })
            ->where('transactions.type', 'expense')
            ->selectRaw('categories.name as category, SUM(transactions.amount) as total')
            ->groupBy('categories.name')
            ->get();

        return view('statistics.index', [
            'monthlyStats' => $monthlyStats,
            'categoryStats' => $categoryStats
        ]);
    }
}
