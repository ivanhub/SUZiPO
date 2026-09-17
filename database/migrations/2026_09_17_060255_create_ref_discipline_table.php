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
        Schema::create('ref_discipline', function (Blueprint $table) {
            $table->comment('Таблица - справочник дисциплин(напр.обуч.)');
            $table->integer('id')->comment('Идентификатор');
            $table->string('name', 100)->nullable()->comment('Наименование дисципплины');
            $table->timestamp('startdate')->nullable()->comment('Дата начала');
            $table->timestamp('enddate')->nullable()->comment('Дата истечения');
            $table->string('kodsap', 10)->nullable();
            $table->integer('isactive');
            $table->string('usercreate', 50)->nullable();
            $table->string('useredit', 50)->nullable();
            $table->timestamp('editdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_discipline');
    }
};
