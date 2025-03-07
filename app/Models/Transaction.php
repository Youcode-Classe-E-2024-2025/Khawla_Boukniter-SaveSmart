<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'family_id',
        'type',
        'category_id',
        'amount',
        'description',
        'goal_id',
        'goal_contribution'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public static function applyFiftyThirtyTwenty($income)
    {
        return [
            'needs' => $income * 0.5,
            'wants' => $income * 0.3,
            'savings' => $income * 0.2
        ];
    }

    public static function optimizeBudget($income, $actualSpending)
    {
        $baseAllocation = self::applyFiftyThirtyTwenty($income);

        if (
            $actualSpending['needs'] > $baseAllocation['needs'] ||
            $actualSpending['wants'] > $baseAllocation['wants']
        ) {

            $totalRequired = $actualSpending['needs'] + $actualSpending['wants'];
            $remainingForSavings = max($income - $totalRequired, $income * 0.1);

            return [
                'needs' => $actualSpending['needs'],
                'wants' => $actualSpending['wants'],
                'savings' => $remainingForSavings
            ];
        }

        return $baseAllocation;
    }

    public static function getBudgetAnalysis($userId, $familyId = null)
    {
        $totalIncome = self::calculateMonthlyIncome($userId, $familyId);

        $expenses = self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->with('category')
            ->get();

        $actualSpending = self::calculateMonthlySpending($userId, $familyId);
        $optimizedBudget = self::optimizeBudget($totalIncome, $actualSpending);

        return [
            'totalIncome' => $totalIncome,
            'targets' => $optimizedBudget,
            'actual' => $actualSpending,
            'details' => [
                'income_transactions' => $totalIncome,
                'expense_transactions' => $expenses
            ]
        ];
    }

    public static function calculateMonthlyIncome($userId, $familyId = null)
    {
        return self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    public static function calculateMonthlySpending($userId, $familyId = null)
    {
        $expenses = self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->with('category')
            ->get();

        return [
            'needs' => $expenses->whereIn('category.type', ['needs'])->sum('amount'),
            'wants' => $expenses->whereIn('category.type', ['wants'])->sum('amount'),
            'savings' => $expenses->whereIn('category.type', ['savings'])->sum('amount')
        ];
    }

    public static function analyzeSpending($userId, $familyId)
    {
        return self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->selectRaw('category_id, type, SUM(amount) as total, COUNT(*) as frequency')
            ->groupBy('category_id', 'type')
            ->get();
    }

    public static function getSpendingTrends($userId, $familyId = null)
    {
        $currentMonth = self::calculateMonthlySpending($userId, $familyId);
        $lastMonth = self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->subMonth())
            ->with('category')
            ->get();

        $lastMonthSpending = [
            'needs' => $lastMonth->whereIn('category.type', ['needs'])->sum('amount'),
            'wants' => $lastMonth->whereIn('category.type', ['wants'])->sum('amount'),
            'savings' => $lastMonth->whereIn('category.type', ['savings'])->sum('amount')
        ];

        return [
            'current_month' => $currentMonth,
            'last_month' => $lastMonthSpending,
            'changes' => [
                'needs' => $currentMonth['needs'] - $lastMonthSpending['needs'],
                'wants' => $currentMonth['wants'] - $lastMonthSpending['wants'],
                'savings' => $currentMonth['savings'] - $lastMonthSpending['savings']
            ]
        ];
    }

    public static function getFinancialInsights($userId, $familyId = null)
    {
        $monthlyIncome = self::calculateMonthlyIncome($userId, $familyId);
        $monthlySpending = self::calculateMonthlySpending($userId, $familyId);

        return [
            'savings_rate' => $monthlyIncome > 0 ? ($monthlySpending['savings'] / $monthlyIncome) * 100 : 0,
            'category_breakdown' => Category::getCategoryBreakdown($userId, $familyId),
            'goals_progress' => Goal::getActiveGoals($userId, $familyId),
        ];
    }
}
