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
        Schema::create('uc_log_studentprotocol', function (Blueprint $table) {
            $table->string('id_log_studentprotocol', 36)->nullable();
            $table->string('id_studentprotocol', 36)->nullable();
            $table->string('id_students', 36)->nullable();
            $table->string('id_protocol', 36)->nullable();
            $table->string('marktheory', 50)->nullable();
            $table->string('markpractic', 50)->nullable();
            $table->string('markprodpractic', 50)->nullable();
            $table->string('markitog', 50)->nullable();
            $table->string('resulttest', 50)->nullable();
            $table->string('assignedgrade', 50)->nullable();
            $table->string('commissiondecision', 50)->nullable();
            $table->string('intervalmark')->nullable();
            $table->string('lastdocumenteduc')->nullable();
            $table->integer('regnumber')->nullable();
            $table->integer('fullregnumber')->nullable();
            $table->integer('seriaregnumber')->nullable();
            $table->string('codemidtrud')->nullable();
            $table->float('counthourfact')->nullable();
            $table->date('begindatefact')->nullable();
            $table->date('enddatefact')->nullable();
            $table->string('fiofact')->nullable();
            $table->string('stafffact')->nullable();
            $table->string('gradefact', 50)->nullable();
            $table->string('departfact')->nullable();
            $table->string('managementfact')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('flagpassed', 50)->nullable();
            $table->string('flagprint', 50)->nullable();
            $table->string('barcode', 50)->nullable();
            $table->string('documentnumber', 50)->nullable();
            $table->string('linktodocument')->nullable();
            $table->string('namecompanyfact')->nullable();
            $table->string('codemvzfact', 50)->nullable();
            $table->date('datedocumentsend')->nullable();
            $table->string('notefield')->nullable();
            $table->string('notefieldlpp')->nullable();
            $table->timestamp('datechange');
            $table->string('Login', 50)->nullable();
            $table->string('Action', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_log_studentprotocol');
    }
};
