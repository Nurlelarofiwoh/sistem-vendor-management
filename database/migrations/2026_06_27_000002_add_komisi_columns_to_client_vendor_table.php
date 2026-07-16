<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom-kolom tracking komisi per-vendor ke tabel pivot client_vendor.
     * Ini memungkinkan Finance mengelola pembayaran komisi secara individual per vendor.
     */
    public function up(): void
    {
        Schema::table('client_vendor', function (Blueprint $table) {
            // Nominal komisi yang harus dibayar vendor ini (diinput oleh Finance)
            $table->decimal('jumlah_komisi', 15, 2)->nullable()->after('vendor_id');

            // Status pelunasan komisi vendor ini
            // Nilai: 'Belum Lunas' → 'Lunas'
            $table->string('status_komisi')->default('Belum Lunas')->after('jumlah_komisi');

            // Timestamp saat Finance menandai vendor ini sebagai lunas
            $table->timestamp('tanggal_bayar_komisi')->nullable()->after('status_komisi');

            // Counter berapa kali email reminder komisi sudah dikirim ke vendor ini
            $table->unsignedTinyInteger('jumlah_reminder_terkirim')->default(0)->after('tanggal_bayar_komisi');

            // Skor dari survey klien (1-5), disalin ke sini saat perhitungan rating murni
            $table->decimal('skor_survey_klien', 3, 1)->nullable()->after('jumlah_reminder_terkirim');

            // Skor kecepatan pembayaran komisi (1, 3, atau 5), dihitung saat tandai lunas
            $table->decimal('skor_kecepatan_komisi', 3, 1)->nullable()->after('skor_survey_klien');
        });
    }

    public function down(): void
    {
        Schema::table('client_vendor', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_komisi',
                'status_komisi',
                'tanggal_bayar_komisi',
                'jumlah_reminder_terkirim',
                'skor_survey_klien',
                'skor_kecepatan_komisi',
            ]);
        });
    }
};
