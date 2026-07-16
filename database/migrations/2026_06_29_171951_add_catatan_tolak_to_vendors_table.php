<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Catatan penolakan dari Manager Commercial saat MoU ditolak
            $table->text('catatan_tolak')->nullable()->after('status_approval');

            // Tanggal vendor ditolak oleh Manager Commercial
            $table->timestamp('tanggal_ditolak')->nullable()->after('catatan_tolak');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['catatan_tolak', 'tanggal_ditolak']);
        });
    }
};
