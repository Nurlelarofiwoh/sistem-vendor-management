<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('technical_meetings', function (Blueprint $table) {
            $table->string('status_tm')->default('aktif')->after('agenda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technical_meetings', function (Blueprint $table) {
            //
        });
    }
};
