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
        Schema::create('sec_roles', function (Blueprint $table) {
            $table->comment('Список доступных ролей');
            $table->integer('role_id')->comment('Идентификатор');
            $table->string('role_name', 50)->nullable()->comment('Наименование роли');
            $table->string('role_enum', 50)->nullable()->comment('Представление роли в системе');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sec_roles');
    }
};
