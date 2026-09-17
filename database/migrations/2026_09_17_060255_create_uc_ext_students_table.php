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
        Schema::create('uc_ext_students', function (Blueprint $table) {
            $table->string('id_student', 36)->nullable();
            $table->string('personnelnumber', 50)->nullable();
            $table->string('fullname', 50)->nullable();
            $table->date('dateofbirth')->nullable();
            $table->string('snils', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('contact', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('staff')->nullable();
            $table->string('grade', 50)->nullable();
            $table->string('management')->nullable();
            $table->string('department')->nullable();
            $table->string('namecompany')->nullable();
            $table->date('flagdismissed')->nullable();
            $table->string('temporarytransfer')->nullable();
            $table->string('codemvz', 50)->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_students');
    }
};
