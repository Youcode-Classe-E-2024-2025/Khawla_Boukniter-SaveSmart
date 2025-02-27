<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'invitation_code',
    ];

    public function members()
    {
        return $this->hasmaney(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function goal()
    {
        return $this->hasMany(Goal::class);
    }

    public static function generateCode()
    {
        do {
            $randomStr = (string)md5(mt_rand());
            $code = strtoupper(substr($randomStr, 0, 8));
        } while (static::where('invitation_code', $code)->exists());

        return $code;
    }
}
