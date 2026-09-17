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
        Schema::create('app_prot_work', function (Blueprint $table) {
            $table->comment('Таблица связи МНОГО (протоколов) - МНОГО(сотрудников)');
            $table->integer('id')->comment('Идентификатор записи');
            $table->integer('prot_id')->comment('Идентификатор протокола');
            $table->integer('work_id')->comment('Идентификатор сотрудника');
            $table->string('estimationteor', 10)->nullable()->comment('Оценка по обучению теории
');
            $table->string('estimationprac', 10)->nullable()->comment('Оценка по обучению практике
');
            $table->integer('id_edtype')->nullable()->comment('Присваиваемая профессия, наименование курса обучения
');
            $table->integer('id_qualif')->nullable()->comment('Присваиваемый разряд
');
            $table->string('sertificate_num', 50)->nullable()->comment('№ удостоверения
');
            $table->integer('id_reason')->nullable()->comment('Причина не проведения обучения
');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_prot_work');
    }
};
