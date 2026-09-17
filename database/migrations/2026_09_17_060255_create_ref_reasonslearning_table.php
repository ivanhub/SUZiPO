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
        Schema::create('ref_reasonslearning', function (Blueprint $table) {
            $table->comment('Таблица - справочник причин обучений');
            $table->integer('id')->comment('Идентификатор');
            $table->string('name', 100)->nullable()->comment('Наименование причины обучения');
            $table->timestamp('datestart')->nullable()->comment('Дата начала');
            $table->timestamp('dateend')->nullable()->comment('Дата истечения');
            $table->string('kodsap', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_reasonslearning');
    }
};
