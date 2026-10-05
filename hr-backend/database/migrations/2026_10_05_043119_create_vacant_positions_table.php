<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacant_positions', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('position_number')->nullable();
            $table->string('position_line')->nullable();
            $table->string('staff_type')->nullable();
            $table->string('vacant_date')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('doc_date')->nullable();
            $table->string('doc_file')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacant_positions');
    }
};
