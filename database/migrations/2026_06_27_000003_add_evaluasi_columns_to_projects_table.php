<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom skor evaluasi klien ke tabel projects.
     * Skor ini diisi saat klien mengirim form evaluasi pasca-event.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Skor numerik (1-5) dari survey evaluasi klien
            $table->decimal('skor_evaluasi_klien', 3, 1)->nullable()->after('is_evaluated');

            // Tanggal event dinyatakan selesai (untuk kalkulasi kecepatan komisi)
            $table->date('tanggal_finish_event')->nullable()->after('skor_evaluasi_klien');

            // Total reminder email komisi yang sudah dikirim (agregat untuk tampilan tabel event)
            $table->unsignedTinyInteger('total_reminder_terkirim')->default(0)->after('tanggal_finish_event');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'skor_evaluasi_klien',
                'tanggal_finish_event',
                'total_reminder_terkirim',
            ]);
        });
    }
};
