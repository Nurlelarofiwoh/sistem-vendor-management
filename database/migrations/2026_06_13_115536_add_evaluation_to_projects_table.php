<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('evaluation_token')->nullable()->unique()->after('status_proyek');
            $table->boolean('is_evaluated')->default(false)->after('evaluation_token');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['evaluation_token', 'is_evaluated']);
        });
    }
};
