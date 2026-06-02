<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserO2Stat extends Model
{
    protected $table = 'user_o2_stats';

    protected $fillable = [
        'user_id',
        'total_pohon',
        'total_o2_kg_per_bulan',
        'last_updated',
    ];

    protected $casts = [
        'total_pohon'           => 'decimal:4',
        'total_o2_kg_per_bulan' => 'decimal:4',
        'last_updated'          => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
