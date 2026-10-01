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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');               // Дата приказа
            $table->integer('within_year')->default(0); // В рамках года
            $table->string('order_number')->nullable(); // Номер приказа (nullable, т.к. на скрине строка 4 пустая)
            $table->date('updated_date')->nullable();   // Дата изменения
            $table->string('updated_by')->nullable();   // Изменил
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
