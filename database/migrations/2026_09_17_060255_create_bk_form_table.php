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
        Schema::create('bk_form', function (Blueprint $table) {
            $table->comment('Справочник (Ref) форм обучения (forms of the education), НЕ ПОДЛЕЖИТ РЕДАКТИРОВАНИЮ И ОБНОВЛЕНИЮ');
            $table->integer('id')->comment('Идентификатор форм обучения');
            $table->string('name', 50)->nullable()->comment('Наименование форм обучения');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_form');
    }
};
