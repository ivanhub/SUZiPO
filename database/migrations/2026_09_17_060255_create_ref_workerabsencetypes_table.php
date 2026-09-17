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
        Schema::create('ref_workerabsencetypes', function (Blueprint $table) {
            $table->integer('id');
            $table->string('work_table_num', 10)->nullable();
            $table->string('fio', 100)->nullable();
            $table->string('absencecode', 100)->nullable();
            $table->string('absencename', 100)->nullable();
            $table->timestamp('datestart')->nullable();
            $table->timestamp('dateend')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_workerabsencetypes');
    }
};
