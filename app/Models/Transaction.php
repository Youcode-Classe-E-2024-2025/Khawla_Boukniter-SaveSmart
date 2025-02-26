<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'family_id',
        'type',
        'category',
        'amount',
        'description',
    ];
}
