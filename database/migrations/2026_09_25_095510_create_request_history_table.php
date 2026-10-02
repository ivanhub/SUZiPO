<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            
            // Связь с заявкой
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();
            
            // Кто внес изменения
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            
            // Тип изменения
            $table->string('action', 50); // created, updated, deleted, rollback
            
            // Измененные данные (JSON)
            $table->json('changes')->nullable();
            
            // К какому сотруднику относится (если изменение сотрудника)
            $table->unsignedBigInteger('request_employee_id')->nullable();
            $table->foreign('request_employee_id')
                  ->references('id')
                  ->on('request_employees')
                  ->nullOnDelete();
            
            // Если это откат - ссылка на оригинальную запись
            $table->unsignedBigInteger('rollback_of')->nullable();
            
            $table->timestamps();
            
            $table->index('request_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_history');
    }
};