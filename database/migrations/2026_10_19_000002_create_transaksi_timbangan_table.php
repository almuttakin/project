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
        Schema::create('transaksi_timbangan', function (Blueprint $table) {
            $table->id();
            $table->string('nota_no', 30)->unique();
            $table->string('no_plat', 15);
            $table->foreignId('unit_pks_id')->constrained('unit_pks')->cascadeOnDelete();
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->float('gross');
            $table->float('tarra');
            $table->float('netto');
            $table->string('status_timbangan', 20)->default('Normal');
            $table->enum('xml_status', ['Pending', 'Generated'])->default('Pending');
            $table->enum('status_sap', ['Pending', 'Sent', 'Failed'])->default('Pending');
            $table->timestamp('sap_sent_at')->nullable();
            $table->string('sap_doc_no', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_timbangan');
    }
};
