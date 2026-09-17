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
        Schema::create('ref_mdb_fields', function (Blueprint $table) {
            $table->comment('Метабаза детализированного описания полей справочников ');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('ref_id')->comment('Идентификатор справочника');
            $table->string('field', 30)->nullable()->comment('Наименование поля (id, name, т.д...)');
            $table->string('field_desc', 50)->nullable()->comment('Описание поля (идентификатор, наименовани, т.д...)');
            $table->string('field_source', 30)->nullable()->comment('Наименование поля в системе Босс-Кадровик');
            $table->string('isdatetime', 1);
            $table->string('issearchablecolumn', 1)->nullable();
            $table->string('isreadonly', 1);
            $table->string('iscomputed', 1);
            $table->string('isbool', 1);
            $table->string('isforeignfield', 1)->nullable();
            $table->string('foreigntablename', 30)->nullable();
            $table->string('foreignfieldname', 30)->nullable();
            $table->string('idforeignkeyfromthis', 30)->nullable();
            $table->string('idparentprimarykey', 30)->nullable();
            $table->string('isforeignkeyfield', 1)->nullable();
            $table->integer('sortfield')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_mdb_fields');
    }
};
