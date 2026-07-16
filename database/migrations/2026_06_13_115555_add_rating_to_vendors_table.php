<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Cek jika kolom rating BELUM ada, maka buat
            if (! Schema::hasColumn('vendors', 'rating')) {
                $table->decimal('rating', 3, 1)->default(0)->after('status_aktif');
            }
            // Cek jika kolom total_review BELUM ada, maka buat
            if (! Schema::hasColumn('vendors', 'total_review')) {
                $table->integer('total_review')->default(0)->after('rating');
            }
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['rating', 'total_review']);
        });
    }
};
