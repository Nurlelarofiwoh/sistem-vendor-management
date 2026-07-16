<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('catatan_tolak');
            }
            if (! Schema::hasColumn('vendors', 'is_new_vendor')) {
                $table->boolean('is_new_vendor')->default(true)->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'is_new_vendor']);
        });
    }
};
