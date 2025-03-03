<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Family;
use App\Models\User;
use App\Models\Transaction;

class FamilyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $family_members = User::where('family_id', $user->family_id)->get();

        $recentTransactions = Transaction::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('family_id', $user->family_id);
        })->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $budgetData = Transaction::getBudgetAnalysis($user->id, $user->family_id);

        return view('family.index', [
            'familyMembers' => $family_members,
            'recentTransactions' => $recentTransactions,
            'budgetData' => $budgetData
        ]);
    }

    public function create()
    {
        return view('family.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string',
        ]);

        $family = Family::create([
            'name' => $request->family_name,
            'owner_id' => Auth::id(),
            'invitation_code' => Family::generateCode(),
        ]);

        // Auth::update(['family_id' => $family->id]);

        $user = Auth::user();
        $user->family_id = $family->id;
        $user->save();

        return redirect()->route('family.index')->with([
            'success' => 'family created successfully',
            'invitation_code' => $family->invitation_code
        ]);
    }

    public function updateBudgetMethod(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'budget_method' => $request->budget_method
        ]);

        $income = Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)->where('type', 'income')->sum('amount');

        $budget = [
            'needs' => $income * 0.5,
            'wants' => $income * 0.3,
            'savings' => $income * 0.2,
        ];

        $spending = [
            'needs' => Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)->where('type', 'expense')->whereHas('category', function ($query) {
                $query->where('type', 'needs');
            })->sum('amount'),

            'savings' => Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)->where('type', 'expense')->whereHas('category', function ($query) {
                $query->where('type', 'needs');
            })->sum('amount'),

            'wants' => Transaction::where('user_id', $user->id)->orWhere('family_id', $user->family_id)->where('type', 'expense')->whereHas('category', function ($query) {
                $query->where('type', 'wants');
            })->sum('amount'),
        ];

        return view('family.budget', [
            'budget' => $budget,
            'spending' => $spending,
            'income' => $income
        ]);
    }
}
