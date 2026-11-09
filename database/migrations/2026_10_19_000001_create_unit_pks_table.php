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
        Schema::create('unit_pks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit', 50);
            $table->string('lokasi', 100);
            $table->string('ip_vpn', 20)->nullable();
            $table->string('ip_wifi', 20)->nullable();
            $table->enum('status_vpn', ['Online', 'Trouble'])->default('Online');
            $table->enum('status_wifi', ['Online', 'Trouble'])->default('Online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_pks');
    }
};
