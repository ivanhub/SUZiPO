<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matrix_po', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->nullable();
            $table->string('code', 50)->nullable();
            $table->string('education_type', 500)->nullable();
            $table->string('profession_name', 500)->nullable();
            $table->string('rank', 100)->nullable();
            $table->text('full_name')->nullable();
            $table->text('study_form')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('theory_hours')->nullable();
            $table->integer('self_study_hours')->nullable();
            $table->integer('practical_hours')->nullable();
            $table->integer('practice_hours')->nullable();
            $table->string('listener_category', 500)->nullable();
            $table->string('group_size', 100)->nullable();
            $table->string('control_form', 300)->nullable();
            $table->string('commission_type', 100)->nullable();
            $table->string('document_type', 200)->nullable();
            $table->string('notes', 500)->nullable();
            $table->string('uchipro', 300)->nullable();
            $table->string('info_system', 300)->nullable();
            $table->text('teacher_requirements')->nullable();
            $table->text('equipment')->nullable();
            $table->text('equipment_location')->nullable();
            $table->string('teacher_fio', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matrix_po');
    }
};