<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class BudgetController extends Controller
{
    public function analysis()
    {
        $user = Auth::user();
        $budgetData = Transaction::getBudgetAnalysis($user->id, $user->family_id);

        return view('budget.analysis', compact('budgetData'));
    }
}
