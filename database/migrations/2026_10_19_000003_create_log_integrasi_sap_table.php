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
        Schema::create('log_integrasi_sap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi_timbangan')->cascadeOnDelete();
            $table->string('no_referensi_sap', 30);
            $table->timestamp('waktu_kirim');
            $table->string('status_respons', 30);
            $table->string('kode_http', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_integrasi_sap');
    }
};
