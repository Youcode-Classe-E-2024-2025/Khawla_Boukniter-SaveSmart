<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
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
}
