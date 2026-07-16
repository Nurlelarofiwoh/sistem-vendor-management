<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->dateTime('jadwal_tm');
            $table->string('lokasi');
            $table->text('agenda');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_meetings');
    }
};
