<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $table = 'user_achievements';

    protected $fillable = [
        'user_id',
        'achievement_id',
        'o2_saat_unlock',
        'diraih_pada',
        'is_notified',
    ];

    protected $casts = [
        'o2_saat_unlock' => 'decimal:4',
        'diraih_pada'    => 'datetime',
        'is_notified'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
