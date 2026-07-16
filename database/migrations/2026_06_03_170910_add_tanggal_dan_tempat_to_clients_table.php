<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->date('tanggal_acara')->nullable()->after('kebutuhan_klien');
            $table->string('tempat_acara')->nullable()->after('tanggal_acara');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['tanggal_acara', 'tempat_acara']);
        });
    }
};
