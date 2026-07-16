<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Menambahkan kolom untuk kebutuhan Management Event & Finance
            $table->string('invoice_path')->nullable()->after('status_proyek');
            $table->string('bukti_pembayaran_path')->nullable()->after('invoice_path');
            $table->text('catatan_finance')->nullable()->after('bukti_pembayaran_path');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['invoice_path', 'bukti_pembayaran_path', 'catatan_finance']);
        });
    }
};
