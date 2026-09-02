<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 50)->default('draft');
            $table->boolean('one_time')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('education_form', 100)->nullable();
            $table->string('employee_type', 50)->nullable();
            $table->string('production_break', 200)->nullable();
            $table->foreignId('provider_id')->nullable()->constrained('requests_providers')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('requests_courses')->nullOnDelete();
            $table->string('country', 255)->default('Россия');
            $table->foreignId('city_id')->nullable()->constrained('requests_cities')->nullOnDelete();
            $table->foreignId('profession_id')->nullable()->constrained('requests_professions')->nullOnDelete();
            $table->foreignId('learn_reason_id')->nullable()->constrained('requests_learn_reasons')->nullOnDelete();
            $table->foreignId('learning_resource_id')->nullable()->constrained('requests_learning_resources')->nullOnDelete();
            $table->foreignId('learning_type_id')->nullable()->constrained('requests_learning_types')->nullOnDelete();
            $table->foreignId('event_type_id')->nullable()->constrained('requests_events_types')->nullOnDelete();
            $table->foreignId('discipline_id')->nullable()->constrained('requests_disciplines')->nullOnDelete();
            $table->string('cost_profit', 50)->nullable();
            $table->foreignId('audience_id')->nullable()->constrained('requests_audiences')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('requests_teachers')->nullOnDelete();
            $table->foreignId('curator_id')->nullable()->constrained('requests_curators')->nullOnDelete();
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};