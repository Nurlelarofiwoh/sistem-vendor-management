<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('link_portofolio')->nullable()->after('range_harga');
            // Kolom JSON ini akan menyimpan semua detail sub-class yang berbeda-beda
            $table->json('detail_spesifikasi')->nullable()->after('link_portofolio');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['link_portofolio', 'detail_spesifikasi']);
        });
    }
};
