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
        Schema::create('sec_users_roles', function (Blueprint $table) {
            $table->comment('Таблица связи много-ролей <> много пользователей (много-ко-многим)');
            $table->integer('id')->comment('Идентификатор записи');
            $table->integer('user_id')->comment('Идентификатор пользователя');
            $table->integer('role_id')->comment('Идентификатор роли');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sec_users_roles');
    }
};
