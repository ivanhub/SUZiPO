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
        Schema::create('app_dem_work', function (Blueprint $table) {
            $table->comment('Таблица связи МНОГО(заявок) - МНОГО (сотрудников)');
            $table->integer('id')->comment('Идентификатор записи');
            $table->integer('dem_id')->comment('Идентификатор заявки');
            $table->integer('edtype_id')->nullable()->comment('Идентификатор вида обучения');
            $table->integer('work_id')->comment('Идентификатор сотрудника');
            $table->integer('instructor_id')->nullable()->comment('Идентификатор инструктора (BK_Workers)');
            $table->integer('qual_id')->nullable()->comment('Идентификатор квалификации');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_dem_work');
    }
};
