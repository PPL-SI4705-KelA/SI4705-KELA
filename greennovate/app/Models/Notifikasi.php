<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'donasi'      => '💝 Donasi',
            'pembayaran'  => '💳 Pembayaran',
            'kegiatan'    => '🌱 Kegiatan',
            'achievement' => '🏅 Achievement',
            default       => '🔔 Sistem',
        };
    }

    public function getTipeColorAttribute(): string
    {
        return match ($this->tipe) {
            'donasi'      => 'green',
            'pembayaran'  => 'blue',
            'kegiatan'    => 'emerald',
            'achievement' => 'yellow',
            default       => 'gray',
        };
    }
}
