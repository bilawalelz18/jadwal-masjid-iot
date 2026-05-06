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
        Schema::table('jadwal_sholats', function (Blueprint $table) {
            // Kita buat nullable() agar jadwal lama yang sudah ada di database tidak error
            $table->foreignId('room_id')->nullable()->after('id')->constrained('rooms')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_sholats', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
};
