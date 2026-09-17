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
        Schema::create('BK_Workers_09-04-2015', function (Blueprint $table) {
            $table->integer('work_id');
            $table->integer('id_bk')->nullable();
            $table->string('work_table_num', 10)->nullable();
            $table->string('work_ferstname', 20)->nullable();
            $table->string('work_middlename', 20)->nullable();
            $table->string('work_lastname', 20)->nullable();
            $table->integer('idprof')->nullable();
            $table->integer('idcategory')->nullable();
            $table->integer('idsubdivision1')->nullable();
            $table->integer('idsubdivision2')->nullable();
            $table->integer('idsubdivision3')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BK_Workers_09-04-2015');
    }
};
