<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Menggunakan string agar fleksibel (Contoh: "Rp 5.000.000 - Rp 15.000.000")
            $table->string('range_harga')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('range_harga');
        });
    }
};
