<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_gelar',
        'threshold_o2',
        'pesan_dampak',
        'icon_badge',
    ];
}
