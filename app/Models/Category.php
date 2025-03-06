<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'family_id',
        'type',
        'scope'
    ];

    public static function getCategoryBreakdown($userId, $familyId)
    {
        return self::join('transactions', 'categories.id', '=', 'transactions.category_id')
            ->where(function ($query) use ($userId, $familyId) {
                $query->where('transactions.user_id', $userId)
                    ->orWhere('transactions.family_id', $familyId);
            })
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.created_at', now()->month)
            ->selectRaw('categories.name, SUM(transactions.amount) as total')
            ->groupBy('categories.name')
            ->get();
    }
}
