<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSholat extends Model
{
    use HasFactory;

    // $guarded = ['id'] artinya semua kolom diizinkan untuk diisi secara otomatis, kecuali kolom 'id'.
    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
