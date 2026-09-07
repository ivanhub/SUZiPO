<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Удаляем старое поле date (если оно есть)
            if (Schema::hasColumn('bookings', 'date')) {
                // Сначала удаляем уникальные индексы, которые используют date
                $table->dropUnique(['audience_id', 'date']);
                $table->dropUnique(['teacher_id', 'date']);
                
                // Затем удаляем само поле
                $table->dropColumn('date');
            }
            
            // Добавляем новые поля start_date и end_date
            if (!Schema::hasColumn('bookings', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            
            // Добавляем новые уникальные индексы для проверки пересечений
            // (Для простоты проверки будем использовать start_date)
            $table->unique(['audience_id', 'start_date']);
            $table->unique(['teacher_id', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['audience_id', 'start_date']);
            $table->dropUnique(['teacher_id', 'start_date']);
            
            $table->dropColumn(['start_date', 'end_date']);
            
            $table->date('date')->nullable();
            $table->unique(['audience_id', 'date']);
            $table->unique(['teacher_id', 'date']);
        });
    }
};