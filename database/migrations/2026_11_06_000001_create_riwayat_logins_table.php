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
        Schema::create('riwayat_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pengguna', 100);
            $table->string('username', 50);
            $table->string('role', 30)->default('Admin');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at')->useCurrent();
            $table->timestamp('logout_at')->nullable();
            $table->string('status', 30)->default('Aktif'); // 'Aktif', 'Logout', 'Sesi Berakhir'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_logins');
    }
};

