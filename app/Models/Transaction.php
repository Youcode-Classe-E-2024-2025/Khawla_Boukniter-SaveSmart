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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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







    // public static function getBudgetAnalysis($userId, $familyId = null)
    // {
    //     $totalIncome = self::where(function ($query) use ($userId, $familyId) {
    //         $query->where('user_id', $userId)
    //             ->orWhere('family_id', $familyId);
    //     })
    //         ->where('type', 'income')
    //         ->whereMonth('created_at', now()->month)
    //         ->get();

    //     $totalIncomeAmount = $totalIncome->sum('amount');

    //     $expenses = self::where(function ($query) use ($userId, $familyId) {
    //         $query->where('user_id', $userId)
    //             ->orWhere('family_id', $familyId);
    //     })
    //         ->where('type', 'expense')
    //         ->whereMonth('created_at', now()->month)
    //         ->with('category')
    //         ->get();

    //     $actualSpending = [
    //         'needs' => $expenses->whereIn('category.type', ['needs'])->sum('amount'),
    //         'wants' => $expenses->whereIn('category.type', ['wants'])->sum('amount'),
    //         'savings' => $expenses->whereIn('category.type', ['savings'])->sum('amount')
    //     ];

    //     $budgetTargets = [
    //         'needs' => $totalIncomeAmount * 0.5,
    //         'wants' => $totalIncomeAmount * 0.3,
    //         'savings' => $totalIncomeAmount * 0.2
    //     ];

    //     if ($actualSpending['needs'] > $budgetTargets['needs']) {
    //         $remainingAmount = $totalIncomeAmount - $actualSpending['needs'];
    //         $budgetTargets = [
    //             'needs' => $actualSpending['needs'],
    //             'wants' => $remainingAmount * 0.6,
    //             'savings' => $remainingAmount * 0.4
    //         ];
    //     }

    //     return [
    //         'totalIncome' => $totalIncomeAmount,
    //         'targets' => $budgetTargets,
    //         'actual' => $actualSpending,
    //         'details' => [
    //             'income_transactions' => $totalIncome,
    //             'expense_transactions' => $expenses
    //         ]
    //     ];
    // }
}
