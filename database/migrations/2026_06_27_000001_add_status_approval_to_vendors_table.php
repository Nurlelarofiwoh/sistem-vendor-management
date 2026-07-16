<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Kolom untuk alur disposisi & approval berjenjang
            // Nilai: 'Pending' → 'Ditinjau' → 'Approved'
            $table->string('status_approval')->default('Pending')->after('status_aktif');

            // Kolom untuk menyimpan path file proposal PDF dari form registrasi publik
            $table->string('proposal_file')->nullable()->after('link_portofolio');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['status_approval', 'proposal_file']);
        });
    }
};
