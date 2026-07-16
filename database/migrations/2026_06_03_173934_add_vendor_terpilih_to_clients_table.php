<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Menyimpan ID vendor yang sudah disetujui Partnership
            $table->foreignId('vendor_terpilih_id')->nullable()->constrained('vendors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['vendor_terpilih_id']);
            $table->dropColumn('vendor_terpilih_id');
        });
    }
};
