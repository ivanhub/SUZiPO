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
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('status', 50)->default('draft')->index();
            $table->boolean('one_time')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('education_form', 100)->nullable();
            $table->string('employee_type', 50)->nullable();
            $table->string('production_break', 200)->nullable();
            $table->bigInteger('provider_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->string('country')->default('Россия');
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('profession_id')->nullable();
            $table->bigInteger('learn_reason_id')->nullable();
            $table->bigInteger('learning_resource_id')->nullable();
            $table->bigInteger('learning_type_id')->nullable();
            $table->bigInteger('event_type_id')->nullable();
            $table->bigInteger('discipline_id')->nullable();
            $table->string('cost_profit', 50)->nullable();
            $table->bigInteger('audience_id')->nullable();
            $table->bigInteger('teacher_id')->nullable();
            $table->bigInteger('curator_id')->nullable();
            $table->timestamps();
            $table->integer('reserve')->nullable();
            $table->string('req_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
