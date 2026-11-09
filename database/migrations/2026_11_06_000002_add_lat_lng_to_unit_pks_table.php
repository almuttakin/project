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
        if (Schema::hasTable('unit_pks')) {
            Schema::table('unit_pks', function (Blueprint $table) {
                if (!Schema::hasColumn('unit_pks', 'lat')) {
                    $table->decimal('lat', 10, 7)->nullable()->after('status_wifi');
                }
                if (!Schema::hasColumn('unit_pks', 'lng')) {
                    $table->decimal('lng', 10, 7)->nullable()->after('lat');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('unit_pks')) {
            Schema::table('unit_pks', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('unit_pks', 'lat')) {
                    $columns[] = 'lat';
                }
                if (Schema::hasColumn('unit_pks', 'lng')) {
                    $columns[] = 'lng';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};

