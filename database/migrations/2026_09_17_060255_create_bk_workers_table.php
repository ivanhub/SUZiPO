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
        Schema::create('bk_workers', function (Blueprint $table) {
            $table->comment('Справчоник сотрудников, подкачивается из Босс-Кадровика (т.е.BK)');
            $table->integer('work_id')->comment('Идентификатор СУЗиПО');
            $table->integer('id_bk')->nullable()->comment('Идентификатор в Босс-кадровике');
            $table->string('work_table_num', 10)->nullable()->comment('Табельный номер сотрудника');
            $table->string('work_ferstname', 20)->nullable()->comment('Имя сотрудника');
            $table->string('work_middlename', 20)->nullable()->comment('Отчество сотрудника');
            $table->string('work_lastname', 30)->nullable()->comment('Фамилия сотрудника');
            $table->integer('idprof')->nullable()->comment('Профессия/ должность');
            $table->integer('idcategory')->nullable()->comment('Разряд сотрудника');
            $table->integer('idsubdivision1')->nullable()->comment('Подразделение 1  (нижний уровень) - непосредственно сектор');
            $table->integer('idsubdivision2')->nullable()->comment('Подразделение 2 (средний уровень)');
            $table->integer('idsubdivision3')->nullable()->comment('Подразделение 3 (верхний уровень)');
            $table->integer('idfirm')->nullable();
            $table->string('namecategory', 50)->nullable();
            $table->string('structname')->nullable();
            $table->string('parentstructname')->nullable();
            $table->string('parentparentstructname')->nullable();
            $table->string('appointname')->nullable();
            $table->string('structid_ebds')->nullable();
            $table->string('parentstructid_ebds')->nullable();
            $table->string('parentparentstructid_ebds')->nullable();
            $table->string('appointid_ebds')->nullable();
            $table->integer('actual')->nullable();
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
        Schema::dropIfExists('bk_workers');
    }
};
