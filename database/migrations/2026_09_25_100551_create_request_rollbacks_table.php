<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_rollbacks', function (Blueprint $table) {
            $table->id();
            
            // Связь с заявкой
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();
            
            // Тип изменения (request / employee)
            $table->string('subject_type', 50); // 'request' или 'employee'
            
            // ID записи (заявка или сотрудник)
            $table->unsignedBigInteger('subject_id');
            
            // ID активности из activity_log (оригинальное изменение)
            $table->unsignedBigInteger('activity_id')->nullable();
            
            // Пользователь который сделал откат
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Данные ДО отката (текущее состояние)
            $table->json('old_data')->nullable();
            
            // Данные ПОСЛЕ отката (состояние после возврата)
            $table->json('new_data')->nullable();
            
            // Причина отката
            $table->string('reason', 500)->nullable();
            
            $table->timestamps();
            
            // Индексы
            $table->index('request_id');
            $table->index(['subject_type', 'subject_id']);
            $table->index('activity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_rollbacks');
    }
};