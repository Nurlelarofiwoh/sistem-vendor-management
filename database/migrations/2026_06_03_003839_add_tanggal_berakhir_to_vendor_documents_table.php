<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_documents', function (Blueprint $table) {
            // Menambahkan kolom tanggal berakhir kontrak setelah status_approval
            $table->date('tanggal_berakhir')->nullable()->after('status_approval');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_documents', function (Blueprint $table) {
            $table->dropColumn('tanggal_berakhir');
        });
    }
};
