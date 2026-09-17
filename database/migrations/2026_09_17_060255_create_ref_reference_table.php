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
        Schema::create('ref_reference', function (Blueprint $table) {
            $table->comment('Справочник описаний справочников системы УЗиПО');
            $table->integer('ref_id')->comment('Идентификатор справочника');
            $table->string('ref_name', 50)->nullable()->comment('Полное название справочника');
            $table->string('ref_shortname', 50)->nullable()->comment('Короткое название справочника');
            $table->string('ref_query', 300)->nullable()->comment('Запрос для получения данных из справочника');
            $table->string('ref_keyfields', 20)->nullable()->comment('Ключевое поле справочника');
            $table->integer('ref_editable')->nullable()->comment('0-справочник из БК (не редактируемый), 1-внутренний справочник');
            $table->string('sourse_query', 300)->nullable()->comment('Запрос на получение данных из системы БОсс-Кадровик');
            $table->string('ref_keyfields_sourse', 20)->nullable()->comment('Ключевое поле в системе Босс-Кадровик');
            $table->string('ref_table', 50)->nullable()->comment('Наименование таблицы справочника');
            $table->string('ref_table_source', 50)->nullable()->comment('Наименование исходной таблицы для справочника');
            $table->string('issearchable', 1);
            $table->string('orderby', 50)->nullable();
            $table->string('isexportable', 1)->nullable();
            $table->string('isupdatedbyperiod', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_reference');
    }
};
