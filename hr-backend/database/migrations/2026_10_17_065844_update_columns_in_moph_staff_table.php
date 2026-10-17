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
            $table->string('agency_name')->nullable()->after('agency_prefix');
            $table->string('agency_internal')->nullable()->after('agency_work');
            $table->string('position_type')->nullable()->after('position_line');
            $table->string('duty_status')->nullable()->after('personal_id_card');
            $table->string('major')->nullable()->after('hire_qual');
            $table->string('specialist_cert')->nullable()->after('major');
            $table->string('institution')->nullable()->after('specialist_cert');
            $table->string('license_org')->nullable()->after('license_expire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moph_staff', function (Blueprint $table) {
            $table->dropColumn([
                'agency_name',
                'agency_internal',
                'position_type',
                'duty_status',
                'major',
                'specialist_cert',
                'institution',
                'license_org',
            ]);
        });
    }
};
