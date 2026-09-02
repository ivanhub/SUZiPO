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
        Schema::create('matrix_dpo', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('row_number')->nullable();
            $table->string('code', 50)->nullable();
            $table->text('dpo_type')->nullable();
            $table->text('program_name')->nullable();
            $table->text('full_name')->nullable();
            $table->text('study_form')->nullable();
            $table->unsignedInteger('total_hours')->nullable();
            $table->unsignedInteger('theoretical_hours')->nullable();
            $table->unsignedInteger('self_study_hours')->nullable();
            $table->unsignedInteger('industrial_practice_hours')->nullable();
            $table->unsignedInteger('practical_hours')->nullable();
            $table->text('student_category')->nullable();
            $table->string('group_capacity', 50)->nullable();
            $table->string('attestation_form', 100)->nullable();
            $table->text('commission_type')->nullable();
            $table->string('issued_document', 100)->nullable();
            $table->text('note')->nullable();
            $table->string('uchi_pro', 100)->nullable();
            $table->string('information_system_entry', 100)->nullable();
            $table->text('teacher_requirements')->nullable();
            $table->text('equipment')->nullable();
            $table->text('equipment_location')->nullable();
            $table->text('teacher_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_dpo');
    }
};