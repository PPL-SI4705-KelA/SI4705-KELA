<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'achievements';

    protected $fillable = [
        'nama',
        'gelar',
        'badge_icon',
        'threshold_o2',
        'pesan_dampak',
    ];

    protected $casts = [
        'threshold_o2' => 'decimal:4',
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
