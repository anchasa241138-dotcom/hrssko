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
        Schema::table('moph_staff', function (Blueprint $table) {
            $table->string('actual_district')->nullable()->after('agency_internal');
            $table->string('actual_prefix')->nullable()->after('actual_district');
            $table->string('actual_name')->nullable()->after('actual_prefix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moph_staff', function (Blueprint $table) {
            $table->dropColumn([
                'actual_district',
                'actual_prefix',
                'actual_name',
            ]);
        });
    }
};
