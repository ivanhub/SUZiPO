<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Явно указываем таблицу requests_audiences
            $table->foreignId('audience_id')
                  ->constrained('requests_audiences')
                  ->onDelete('cascade');
                  
            // Явно указываем таблицу requests_teachers
            $table->foreignId('teacher_id')
                  ->constrained('requests_teachers')
                  ->onDelete('cascade');
                  
            $table->date('date');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Уникальность: одна аудитория не может быть забронирована дважды на одну дату
            $table->unique(['audience_id', 'date']);
            
            // Уникальность: преподаватель не может вести занятия в двух аудиториях одновременно
            $table->unique(['teacher_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};