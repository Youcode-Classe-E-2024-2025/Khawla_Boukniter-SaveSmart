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

    public static function getBudgetAnalysis($userId, $familyId = null)
    {
        // Get total income for the month
        $totalIncome = self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'income')
            ->whereMonth('created_at', now()->month)
            ->get();

        Log::info('Income transactions:', $totalIncome->toArray());

        // Calculate total income amount
        $totalIncomeAmount = $totalIncome->sum('amount');

        // Get all expenses with their categories
        $expenses = self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('type', 'expense')
            ->whereMonth('created_at', now()->month)
            ->with('category')
            ->get();

        Log::info('Expense transactions:', $expenses->toArray());

        // Group expenses by category type
        $actualSpending = [
            'needs' => $expenses->whereIn('category.type', ['needs'])->sum('amount'),
            'wants' => $expenses->whereIn('category.type', ['wants'])->sum('amount'),
            'savings' => $expenses->whereIn('category.type', ['savings'])->sum('amount')
        ];

        $budgetTargets = [
            'needs' => $totalIncomeAmount * 0.5,
            'wants' => $totalIncomeAmount * 0.3,
            'savings' => $totalIncomeAmount * 0.2
        ];

        return [
            'totalIncome' => $totalIncomeAmount,
            'targets' => $budgetTargets,
            'actual' => $actualSpending,
            'details' => [
                'income_transactions' => $totalIncome,
                'expense_transactions' => $expenses
            ]
        ];
    }
}
