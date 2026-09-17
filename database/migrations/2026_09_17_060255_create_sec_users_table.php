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
        Schema::create('sec_users', function (Blueprint $table) {
            $table->comment('Список пользователей системы СУЗиПО');
            $table->integer('user_id')->comment('Идентификатор');
            $table->string('user_name', 50)->nullable()->comment('Имя пользователя');
            $table->string('user_mail', 50)->nullable()->comment('Адресс электронной почты');
            $table->string('user_login', 50)->nullable()->comment('Логин в системе (просто для сопоставления)');
            $table->integer('id_schol_division')->nullable()->comment('Идентификатор учебного подразделения (для методистов только)');
            $table->string('user_telefon', 50)->nullable()->comment('Номер телефона пользователя');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sec_users');
    }
};
