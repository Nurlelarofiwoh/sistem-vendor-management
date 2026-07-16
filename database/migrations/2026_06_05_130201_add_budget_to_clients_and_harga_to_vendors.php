<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Menambah kolom budget (angka nominal)
            $table->bigInteger('budget')->nullable()->after('tanggal_acara');
        });

        Schema::table('vendors', function (Blueprint $table) {
            // Menghapus range_harga lama, mengganti dengan harga pasti (angka nominal)
            $table->dropColumn('range_harga');
            $table->bigInteger('harga')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('budget');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->string('range_harga')->nullable();
            $table->dropColumn('harga');
        });
    }
};
