<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->default('info'); // Tipe: 'info' (biru), 'warning' (kuning), 'success' (hijau), 'error' (merah)
            $table->string('ikon')->default('notifications'); // Nama ikon Google Material
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifikasis');
    }
};
