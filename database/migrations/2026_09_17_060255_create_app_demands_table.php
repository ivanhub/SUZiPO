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
        Schema::create('app_demands', function (Blueprint $table) {
            $table->comment('Таблица заявок на обучение');
            $table->integer('dem_id');
            $table->integer('dem_num')->comment('Номер заявки');
            $table->timestamp('dem_date')->comment('Дата оформления заявки');
            $table->integer('dem_year')->comment('Год оформления заявки (автомат-е, фиктив-е, для ограничения)');
            $table->timestamp('date_edit')->comment('Дата последнего редактирования заявки');
            $table->integer('id_user_create')->comment('Автор заявки');
            $table->integer('id_user_edit')->comment('Редактор заявки');
            $table->integer('id_vuz')->nullable()->comment('Наименование учебного заведения');
            $table->integer('id_course')->nullable()->comment('Наименование курса');
            $table->timestamp('date_start')->comment('Дата начала курса');
            $table->timestamp('date_end')->comment('Дата окончания курса');
            $table->integer('id_country')->comment('Место проведения: страна');
            $table->integer('id_city')->nullable()->comment('Место проведения: город');
            $table->integer('id_prof')->nullable()->comment('Профессия/ должность, присваиваемая по результатам обучения');
            $table->integer('id_form')->nullable()->comment('Форма образования');
            $table->integer('row_verion')->comment('Версия строки, при каждом изменении увеличивается на 1');
            $table->integer('dem_status')->comment('Состояние (статус) заявки');
            $table->integer('isworker')->comment('ИТР/рабочие');
            $table->integer('withtake_off')->comment('С отрывом от производства');
            $table->integer('id_schol_division')->nullable()->comment('Идентификатор учебного подразделения (только для некоторых состояний)');
            $table->integer('istemp')->nullable()->comment('Признак временного хранения, по умолчанию NULL, 1- значит временная заявка');
            $table->string('sessionid', 50)->nullable()->comment('Значение идентификатора сессии для времнных заявок, по умолчанию NULL');
            $table->integer('id_refuse')->nullable()->comment('Причина отказа');
            $table->timestamp('date_change_state')->nullable()->comment('Дата изменения статуса');
            $table->integer('fromprot')->nullable()->comment('Метка о создании протокола из списка неаттестованных');
            $table->string('isdisposable', 1)->comment('Признак одноразовой заявки 0 - для постоянных провайдеров; 1 - непостоянному (одноразовая заявка)');
            $table->string('isplaned', 1)->comment('Признак плановой заявки 1- плановая, 0 - вне плана');
            $table->integer('idauthor')->nullable()->comment('Автор курса');
            $table->integer('iddirecteduc')->nullable()->comment('Направление обучения');
            $table->integer('idtypeeduc')->nullable()->comment('Тип обучения');
            $table->string('iscorporativeeduc', 1)->nullable();
            $table->integer('iddirectexpenses')->nullable();
            $table->integer('id_city_new')->nullable();
            $table->string('id_city_old', 50)->nullable();
            $table->string('costprofit')->nullable();
            $table->integer('idreasonslearning')->nullable();
            $table->integer('iddiscipline')->nullable();
            $table->integer('idtypeoftrainingandevaluation')->nullable();
            $table->integer('idlearningresource')->nullable();
            $table->integer('ideventtype')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_demands');
    }
};
