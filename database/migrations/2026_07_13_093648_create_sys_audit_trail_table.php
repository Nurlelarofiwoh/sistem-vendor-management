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
        Schema::create('sys_audit_trail', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->decimal('nilai_sebelum', 3, 1);
            $table->decimal('nilai_sesudah', 3, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_audit_trail');
    }
};
