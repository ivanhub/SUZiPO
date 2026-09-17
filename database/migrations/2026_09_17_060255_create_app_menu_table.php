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
        Schema::create('app_menu', function (Blueprint $table) {
            $table->comment('Таблица пунктов меню для перехода по страницам');
            $table->integer('menu_id')->comment('Идентификатор пункта меню');
            $table->string('menu_text', 50)->nullable()->comment('Текст ссылки');
            $table->string('menu_ancor', 55)->nullable()->comment('Ссылка на страницу');
            $table->integer('menu_kod')->comment('Код пункта меню');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_menu');
    }
};
