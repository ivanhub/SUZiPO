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
        Schema::create('matrix_ot', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('row_number')->nullable();
            $table->string('code', 50)->nullable();
            $table->string('training_type', 100)->nullable();
            $table->text('program_name')->nullable();
            $table->text('full_name')->nullable();
            $table->text('study_form')->nullable();
            $table->unsignedInteger('total_hours')->nullable();
            $table->float('fulltime_theoretical_hours')->nullable();
            $table->float('distance_theoretical_hours')->nullable();
            $table->unsignedInteger('industrial_practice_hours')->nullable();
            $table->float('practical_hours')->nullable();
            $table->text('student_category')->nullable();
            $table->string('group_capacity', 50)->nullable();
            $table->string('control_form', 200)->nullable();
            $table->text('commission_type')->nullable();
            $table->string('issued_document', 200)->nullable();
            $table->text('note')->nullable();
            $table->string('uchi_pro', 100)->nullable();
            $table->text('information_system_entry')->nullable();
            $table->text('equipment')->nullable();
            $table->text('equipment_location')->nullable();
            $table->text('teacher_name')->nullable();
            $table->string('code_ucjung', 100)->nullable();
            $table->string('code_ul', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_ot');
    }
};