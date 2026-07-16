<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_vendor');
            $table->string('kategori_jasa');
            $table->string('email')->unique(); // Untuk auto broadcast
            $table->string('no_telepon');
            $table->decimal('rating', 3, 2)->default(0.00); // Format: 5.00
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
