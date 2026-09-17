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
        Schema::create('uc_ext_report', function (Blueprint $table) {
            $table->string('id_report', 36)->nullable();
            $table->string('id_protocol', 36)->nullable();
            $table->string('specialist')->nullable();
            $table->string('numbergroup', 50)->nullable();
            $table->integer('countworker')->nullable();
            $table->integer('countleader')->nullable();
            $table->integer('countspecialist')->nullable();
            $table->integer('Show')->nullable();
            $table->integer('noshow')->nullable();
            $table->integer('passed')->nullable();
            $table->integer('nopassed')->nullable();
            $table->integer('fulltime')->nullable();
            $table->integer('distance')->nullable();
            $table->integer('countwoman')->nullable();
            $table->integer('countman')->nullable();
            $table->integer('countung')->nullable();
            $table->integer('countservise')->nullable();
            $table->integer('countothercomp')->nullable();
            $table->integer('countcash')->nullable();
            $table->string('teacher1')->nullable();
            $table->string('specialistuc', 50)->nullable();
            $table->string('curatorurp', 50)->nullable();
            $table->string('audiencenumber', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_report');
    }
};
