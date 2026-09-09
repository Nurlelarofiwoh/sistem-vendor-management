<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom `skor_finansial` ke tabel vendors.
     *
     * Skor ini merepresentasikan kepatuhan bayar komisi vendor, TERPISAH dari rating klien:
     *   3 = Lancar         (default semua vendor) — boleh masuk shortlist
     *   2 = Warning        (telat bayar, peringatan) — masih boleh masuk shortlist
     *   1 = Sengketa       (telat > 90 hari / dispute) — DIEKSKLUSI dari shortlist
     *
     * Default 3 memastikan semua vendor existing otomatis masuk kategori Lancar
     * tanpa perlu seeder ulang.
     *
     * Sekaligus: pastikan kolom `rating` nullable agar vendor baru tidak punya
     * nilai dummy. Jika kolom belum nullable, migration ini memperbaikinya.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->tinyInteger('skor_finansial')->default(3)->after('rating');
        });

        // Pastikan kolom rating nullable (mungkin sudah, tapi aman dicek ulang)
        Schema::table('vendors', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('skor_finansial');
        });
    }
};
