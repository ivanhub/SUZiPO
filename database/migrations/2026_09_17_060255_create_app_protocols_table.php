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
        Schema::create('app_protocols', function (Blueprint $table) {
            $table->comment('Таблица протоколов обучения');
            $table->increments('prot_id');
            $table->string('prot_num', 50)->nullable()->comment('Номер протокола');
            $table->timestamp('prot_date')->comment('Дата оформления протокола');
            $table->timestamp('date_edit')->comment('Дата последнего редактирования протокола');
            $table->integer('id_user_create')->comment('Автор протокола');
            $table->integer('id_user_edit')->comment('Редактор протокола');
            $table->timestamp('date_start')->comment('Дата начала курса');
            $table->timestamp('date_end')->comment('Дата окончания курса');
            $table->integer('id_prof')->nullable()->comment('Профессия/ должность, присваиваемая по результатам обучения');
            $table->integer('row_version')->nullable()->comment('Версия строки, при каждом изменении увеличивается на 1');
            $table->integer('prot_status')->nullable()->comment('Статус протокла');
            $table->integer('dem_id')->nullable()->comment('Идентификатор заявки, для которой создается протокол обучения');
            $table->integer('group_num')->nullable()->comment('Номер группы
');
            $table->string('order_num', 50)->nullable()->comment('Номер приказа
');
            $table->timestamp('order_date')->nullable()->comment('Дата назначения приказа
');
            $table->decimal('Cost', 18)->nullable()->comment('Стоимость обучения для 1 человека за курс
');
            $table->integer('teorcounthours')->nullable()->comment('Количество часов по программе теоретического обучения
');
            $table->integer('praccounthours')->nullable()->comment('Количество часов по программе практического обучения
');
            $table->integer('teorfacthours')->nullable()->comment('Фактически пройдено (теория)
');
            $table->integer('pracfacthours')->nullable()->comment('Фактически пройдено (практика)
');
            $table->integer('refuse_id')->nullable()->comment('Причина отказа');
            $table->timestamp('date_change_state')->nullable()->comment('Дата изменения статуса');
            $table->integer('typedoc_id')->nullable()->comment('Тип документа об обучении');
            $table->timestamp('prot_create')->nullable()->comment('Дата создания протокола');
            $table->timestamp('directiondate')->nullable()->comment('Дата распоряжения');
            $table->integer('directionnumber')->nullable()->comment('Номер распоряжения');
            $table->decimal('costvatoldvalues', 18)->nullable();
            $table->integer('iddateofagreementeducation')->nullable();
            $table->integer('costvat')->nullable();
            $table->decimal('hoursbyprogram', 6)->nullable();
            $table->decimal('theoryhours', 6)->nullable();
            $table->decimal('practicehours', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_protocols');
    }
};
