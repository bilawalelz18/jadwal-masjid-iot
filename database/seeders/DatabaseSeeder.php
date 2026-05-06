<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Device;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/*class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // (Biarkan kode pembuatan User Admin Anda yang sudah ada di sini)
        // User::create([...]);

        // --- TAMBAHKAN KODE DI BAWAH INI ---
        User::create([
            'name' => 'admin1',
            'email' => 'admin1@admin.com', // Ganti dengan email Anda
            'password' => bcrypt('admin123'), // Ganti dengan password Anda
        ]);

        // 1. Membuat Ruangan
        $ruangUtama = Room::create([
            'nama_ruangan' => 'Ruang Utama',
            'deskripsi' => 'Area sholat utama jamaah'
        ]);

        $aula = Room::create([
            'nama_ruangan' => 'Aula Masjid',
            'deskripsi' => 'Area serbaguna dan kajian'
        ]);

        // 2. Membuat Perangkat IoT (ESP32) dan menyambungkannya ke Ruangan
        Device::create([
            'room_id' => $ruangUtama->id,
            'device_id' => 'ESP32-UTAMA-01',
            'api_key' => 'secret-utama-123',
            'nama_perangkat' => 'Pewangi Utama Depan',
            'status_koneksi' => 'online'
        ]);

        Device::create([
            'room_id' => $aula->id,
            'device_id' => 'ESP32-AULA-01',
            'api_key' => 'secret-aula-123',
            'nama_perangkat' => 'Pewangi Aula',
            'status_koneksi' => 'offline'
        ]);
    }
}
