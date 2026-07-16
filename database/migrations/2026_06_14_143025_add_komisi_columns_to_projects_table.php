<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->date('tanggal_komisi_jatuh_tempo')->nullable()->after('catatan_operasional');
            $table->date('tanggal_komisi_dibayar')->nullable()->after('tanggal_komisi_jatuh_tempo');
            $table->decimal('penalti_rating', 3, 1)->default(0)->after('tanggal_komisi_dibayar');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['tanggal_komisi_jatuh_tempo', 'tanggal_komisi_dibayar', 'penalti_rating']);
        });
    }
};
