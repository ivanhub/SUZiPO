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
        Schema::create('ref_classrooms', function (Blueprint $table) {
            $table->integer('id');
            $table->string('classroomnumber', 50)->nullable();
            $table->string('classroomplacement', 50)->nullable();
            $table->integer('idcity');
            $table->string('responsibleperson', 50)->nullable();
            $table->string('seatcount', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_classrooms');
    }
};
