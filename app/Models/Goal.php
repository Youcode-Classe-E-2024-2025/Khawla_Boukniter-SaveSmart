<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'family_id',
        'name',
        'target_amount',
        'current_amount',
        'category',
        'target_date',
        'description',
    ];

    protected $casts = [
        'target_date' => 'date',
        'current_amount' => 'float',
        'target_amount' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function getActiveGoals($userId, $familyId)
    {
        return self::where(function ($query) use ($userId, $familyId) {
            $query->where('user_id', $userId)
                ->orWhere('family_id', $familyId);
        })
            ->where('target_date', '>', now())
            ->get();
    }

    public function updateProgress()
    {
        $this->current_amount = $this->transactions()->where('goal_contribution', true)->sum('amount');
        $this->save();
    }
}
