<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = ['id'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function jadwalSholats()
    {
        return $this->hasMany(JadwalSholat::class);
    }
}
