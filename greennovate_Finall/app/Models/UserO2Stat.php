<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserO2Stat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_pohon',
        'total_o2_kg_per_bulan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
