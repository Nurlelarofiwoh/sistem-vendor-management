<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->decimal('jumlah_komisi', 15, 2); // atau integer, sesuaikan dengan milik Anda

            // UBAH BARIS INI MENJADI STRING:
            $table->string('status_pembayaran')->default('pending');

            $table->date('tenggat_waktu');
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_payments');
    }
};
