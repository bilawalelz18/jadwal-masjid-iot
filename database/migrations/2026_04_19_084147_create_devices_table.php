<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel rooms (Jika ruangan dihapus, alat di dalamnya ikut terhapus)
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();

            // Kredensial IoT
            $table->string('device_id')->unique()->comment('Mac Address / Unique ID ESP32');
            $table->string('api_key')->unique()->comment('Token rahasia untuk autentikasi API');

            $table->string('nama_perangkat')->nullable(); // Contoh: ESP32-Kiri
            $table->enum('status_koneksi', ['online', 'offline'])->default('offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
