<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reset rating ke NULL untuk semua vendor yang masih berstatus "baru/belum dinilai".
     *
     * Langkah 1: pastikan kolom rating nullable (kolom lama menggunakan default(0.00)).
     * Langkah 2: update semua vendor baru (is_new_vendor = true) dari 4.5 / 0.00 → NULL.
     *
     * NULL dipilih sebagai penanda "belum ada data penilaian dari klien" sehingga
     * vendor ini masuk Challenger Slot pada algoritma Top-N Curated Shortlisting.
     */
    public function up(): void
    {
        // Langkah 1: Jadikan kolom rating nullable agar UPDATE ke NULL tidak error
        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->nullable()->change();
        });

        // Langkah 2: Reset semua vendor baru ke NULL
        DB::table('vendors')
            ->where('is_new_vendor', true)
            ->update(['rating' => null]);
    }

    public function down(): void
    {
        // Kembalikan 4.5 sebagai nilai cold-start lama jika rollback diperlukan.
        DB::table('vendors')
            ->where('is_new_vendor', true)
            ->update(['rating' => 4.5]);
    }
};
