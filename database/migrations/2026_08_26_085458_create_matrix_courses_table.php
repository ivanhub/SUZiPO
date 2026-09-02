<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matrix_courses', function (Blueprint $table) {
            $table->id();
            $table->string('program', 500)->nullable();
            $table->integer('number')->nullable();
            $table->string('code', 50)->nullable();
            $table->string('education_type', 500)->nullable();
            $table->text('program_name')->nullable();
            $table->text('full_name')->nullable();
            $table->string('study_form', 500)->nullable();
            $table->integer('hours')->nullable();
            $table->integer('theory_hours')->nullable();
            $table->integer('self_study_hours')->nullable();
            $table->integer('practical_hours')->nullable();
            $table->integer('practice_hours')->nullable();
            $table->string('listener_category', 500)->nullable();
            $table->string('group_size', 100)->nullable();
            $table->string('control_form', 200)->nullable();
            $table->string('commission_type', 100)->nullable();
            $table->string('document_type', 200)->nullable();
            $table->text('notes')->nullable();
            $table->string('uchipro', 200)->nullable();
            $table->string('info_system', 500)->nullable();
            $table->text('teacher_requirements')->nullable();
            $table->text('equipment')->nullable();
            $table->text('equipment_location')->nullable();
            $table->string('teacher_fio', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matrix_courses');
    }
};