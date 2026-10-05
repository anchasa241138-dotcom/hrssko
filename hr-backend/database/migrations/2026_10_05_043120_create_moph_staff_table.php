<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moph_staff', function (Blueprint $table) {
            $table->id();
            $table->string('agency_prefix')->nullable();
            $table->string('agency_district')->nullable();
            $table->string('agency_sub_district')->nullable();
            $table->string('agency_group')->nullable();
            $table->string('agency_work')->nullable();
            $table->string('agency_match')->nullable();
            $table->string('position_number')->nullable();
            $table->string('position_level')->nullable();
            $table->string('position_line')->nullable();
            $table->string('position_status')->nullable();
            $table->string('staff_type')->nullable();
            $table->string('personal_prefix')->nullable();
            $table->string('personal_fname')->nullable();
            $table->string('personal_lname')->nullable();
            $table->string('personal_id_card')->nullable();
            $table->string('hire_date')->nullable();
            $table->string('hire_qual')->nullable();
            $table->string('grad_date')->nullable();
            $table->string('gpa')->nullable();
            $table->string('license_name')->nullable();
            $table->string('license_no')->nullable();
            $table->string('license_issue')->nullable();
            $table->string('license_expire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moph_staff');
    }
};
