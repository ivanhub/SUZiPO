<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_employees', function (Blueprint $table) {
            $table->id();
            
            // Связь с заявкой
            $table->foreignId('request_id')
                  ->constrained('requests')
                  ->cascadeOnDelete();
            
            // Связь с сотрудником SAP
            $table->unsignedBigInteger('user_sap_id')->nullable();
            $table->foreign('user_sap_id')
                  ->references('id')
                  ->on('all_users_sap')
                  ->nullOnDelete();
            
            // Ручной ввод (если нет табельного номера)
            $table->string('last_name', 255)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('middle_name', 255)->nullable();
            
            // Дополнительные поля (пункт 5)
            $table->date('absence_start_date')->nullable();
            $table->date('absence_end_date')->nullable();
            $table->string('absence_type', 50)->nullable(); // очно/заочно/очно-заочное
            $table->date('distance_learning_date')->nullable();
            $table->date('fulltime_learning_date')->nullable();
            $table->text('note')->nullable();
            
            // Поля для пункта 9
            $table->date('document_issue_date')->nullable();
            $table->string('reissue_period', 50)->nullable(); // "6 месяцев", "1 год", "2 года", "3 года"
            
            // Проверки (пункты 7, 8)
            $table->string('status', 20)->default('active'); // active/blocked/warning/dismissed/expired
            $table->string('warning_type', 50)->nullable(); // 'duplicate', 'position_changed', 'dismissed', 'expired'
            $table->string('warning_message', 500)->nullable();
            
            $table->timestamps();
            
            // Индексы
            $table->index('request_id');
            $table->index('user_sap_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_employees');
    }
};