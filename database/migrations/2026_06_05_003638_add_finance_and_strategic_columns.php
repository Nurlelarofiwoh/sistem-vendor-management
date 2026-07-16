<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penambahan untuk Finance di tabel projects
        Schema::table('projects', function (Blueprint $table) {
            $table->string('file_invoice')->nullable();
            $table->string('file_bukti_tf')->nullable();
            $table->string('status_pembayaran')->default('menunggu_invoice');
            // Status: menunggu_invoice -> menunggu_pembayaran -> menunggu_validasi -> lunas
        });

        // Penambahan untuk Strategic di tabel vendors
        Schema::table('vendors', function (Blueprint $table) {
            $table->date('tanggal_kontrak_habis')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['file_invoice', 'file_bukti_tf', 'status_pembayaran']);
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kontrak_habis']);
        });
    }
};
