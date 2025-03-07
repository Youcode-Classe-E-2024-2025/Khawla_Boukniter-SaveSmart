<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Family;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Goal;
use Illuminate\Support\Facades\Log;

class FamilyController extends Controller
{
    public function index()
    {
        return view('family.index', $this->getFamilyData(Auth::user()));
    }

    private function getFamilyData($user)
    {
        $income = Transaction::calculateMonthlyIncome($user->id, $user->family_id);

        return [
            'familyMembers' => User::where('family_id', $user->family_id)->get(),
            'recentTransactions' => $this->getRecentTransactions($user),
            'basicBudget' => Transaction::applyFiftyThirtyTwenty($income),
            'optimizedBudget' => Transaction::getBudgetAnalysis($user->id, $user->family_id),
            'spendingTrends' => Transaction::getSpendingTrends($user->id, $user->family_id),
            'insights' => Transaction::getFinancialInsights($user->id, $user->family_id),
            'family' => Family::find($user->family_id)
        ];
    }

    private function getRecentTransactions($user)
    {
        return Transaction::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('family_id', $user->family_id);
        })->orderBy('created_at', 'desc')->take(3)->get();
    }

    private function calculateBudget($income, $spending = null)
    {
        // Initial 50/30/20 allocation
        $budget = [
            'needs' => $income * 0.5,
            'wants' => $income * 0.3,
            'savings' => $income * 0.2
        ];

        if ($spending && $spending['needs'] > $budget['needs']) {
            // Calculate the excess amount
            $excess = $spending['needs'] - $budget['needs'];

            // Redistribute remaining amount (40%) between wants and savings
            $remainingAmount = $income - $spending['needs'];

            // Maintain the original 3:2 ratio between wants and savings
            $budget['needs'] = $spending['needs'];
            $budget['wants'] = $remainingAmount * 0.6;  // 60% of remaining
            $budget['savings'] = $remainingAmount * 0.4; // 40% of remaining
        }

        return $budget;
    }

    private function getSpending($user)
    {
        $query = Transaction::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('family_id', $user->family_id);
        })->where('type', 'expense');

        return [
            'needs' => (clone $query)->whereHas('category', fn($q) => $q->where('type', 'needs'))->sum('amount'),
            'wants' => (clone $query)->whereHas('category', fn($q) => $q->where('type', 'wants'))->sum('amount'),
            'savings' => (clone $query)->whereHas('category', fn($q) => $q->where('type', 'savings'))->sum('amount'),
        ];
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

        if ($user->account_type === 'family') {
            $update = Family::where('id', $user->family_id)->update([
                'budget_method' => $request->budget_method
            ]);

            Log::info('Family Budget Update Result', [
                'family_id' => $user->family_id,
                'update_success' => $update,
                'new_method' => $request->budget_method
            ]);
        } else {
            $user->update(['budget_method' => $request->budget_method]);
        }

        $income = Transaction::calculateMonthlyIncome($user->id, $user->family_id);

        $spending = $this->getSpending($user);

        if ($request->budget_method === 'intelligent-allocation') {
            $spendingPatterns = Transaction::analyzeSpending($user->id, $user->family_id);
            $goals = Goal::getActiveGoals($user->id, $user->family_id);
            $budget = $this->calculateIntelligentBudget($income, $spendingPatterns, $goals);
        } else {
            $budget = Transaction::optimizeBudget($income, $spending);
        }

        return view('family.index', [
            ...$this->getFamilyData($user),
            'budget' => $budget,
            'spending' => $spending,
            'income' => $income,
            'adjustmentDetails' => [
                'needsExceeded' => $spending['needs'] > ($income * 0.5),
                'wantsExceeded' => $spending['wants'] > ($income * 0.3),
                'savingsImpact' => $budget['savings'] < ($income * 0.2)
            ]
        ]);
    }

    private function calculateIntelligentBudget($income, $spending, $goals)
    {
        $baseNeeds = $income * 0.5;
        $baseWants = $income * 0.3;
        $baseSavings = $income * 0.2;

        $actualNeeds = $spending->where('type', 'needs')->sum('total');
        $actualWants = $spending->where('type', 'wants')->sum('total');
        $actualSavings = $spending->where('type', 'savings')->sum('total');

        $alerts = [];
        $adjustments = [];

        if ($actualNeeds > $baseNeeds) {
            $excessNeeds = $actualNeeds - $baseNeeds;
            $adjustments['wants'] = - ($excessNeeds * 0.6);
            $adjustments['savings'] = - ($excessNeeds * 0.4);
            $alerts['needs'] = "Needs exceeded by " . number_format($excessNeeds, 2) . " MAD";
        }

        if ($actualWants > $baseWants) {
            $excessWants = $actualWants - $baseWants;
            $adjustments['needs'] = - ($excessWants * 0.7);
            $adjustments['savings'] = - ($excessWants * 0.3);
            $alerts['wants'] = "Wants exceeded by " . number_format($excessWants, 2) . " MAD";
        }

        if ($actualSavings > $baseSavings) {
            $excessSavings = $actualSavings - $baseSavings;
            $adjustments['needs'] = - ($excessSavings * 0.7);
            $adjustments['wants'] = - ($excessSavings * 0.3);
            $alerts['savings'] = "Savings exceeded by " . number_format($excessSavings, 2) . " MAD";
        }

        return [
            'needs' => $baseNeeds + ($adjustments['needs'] ?? 0),
            'wants' => $baseWants + ($adjustments['wants'] ?? 0),
            'savings' => $baseSavings + ($adjustments['savings'] ?? 0),
            'alerts' => $alerts
        ];
    }
}
