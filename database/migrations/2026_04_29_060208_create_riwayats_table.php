<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('riwayats', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->default('01');
            $table->string('trigger_aksi'); // isi: otomatis_jadwal / smart_trigger / manual
            $table->string('status'); // isi: berhasil / gagal
            $table->integer('sisa_parfum_ml'); // mencatat sisa cairan saat kejadian
            $table->timestamps(); // otomatis membuat kolom created_at (tanggal & waktu)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};
