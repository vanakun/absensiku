<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'lokasi_absen',
        'jam_masuk',
        'jam_pulang',
        'reason',
        'surat_izin',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
