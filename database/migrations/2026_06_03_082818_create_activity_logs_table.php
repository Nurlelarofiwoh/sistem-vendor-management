<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Nama staf CS yang input
            $table->string('aksi');      // Jenis tindakan (misal: Input Klien)
            $table->string('deskripsi'); // Detail (misal: Menambahkan PT ABC)
            $table->timestamps();        // Otomatis mencatat jam & tanggal
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
