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
        Schema::create('jadwal_sholats', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable(); // <-- TAMBAHKAN BARIS INI
            $table->string('nama_sholat');
            $table->time('waktu');
            $table->boolean('is_api')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_sholats');
    }
};
