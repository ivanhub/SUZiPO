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
        Schema::create('srv_errors', function (Blueprint $table) {
            $table->comment('Хранение сообщений об ошибках');
            $table->integer('id_msg')->comment('Идентификатор');
            $table->string('computername', 50)->nullable()->comment('Название компьютера');
            $table->string('username', 50)->nullable()->comment('Имя пользователя');
            $table->string('txtmsg', 500)->nullable()->comment('Текст сообщения об ошибке');
            $table->timestamp('timeerror')->nullable()->comment('Время возникновения ошибки');
            $table->string('detailinfo', 3000)->nullable()->comment('Содержимое стека');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv_errors');
    }
};
