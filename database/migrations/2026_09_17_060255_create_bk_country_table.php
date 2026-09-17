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
        Schema::create('bk_country', function (Blueprint $table) {
            $table->comment('Справочник (Ref) стран');
            $table->integer('id')->comment('Идентификатор страны');
            $table->integer('id_bk')->nullable()->comment('Идентификатор БК');
            $table->string('name', 50)->nullable()->comment('Название страны');
            $table->string('id_ebds')->nullable();
            $table->string('usercreate', 50)->nullable();
            $table->string('useredit', 50)->nullable();
            $table->timestamp('editdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_country');
    }
};
