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
        Schema::create('bk_orders', function (Blueprint $table) {
            $table->comment('Справочник (Ref) приказов');
            $table->integer('id')->comment('Идентификатор');
            $table->integer('id_bk')->nullable()->comment('Идентификатор БК');
            $table->string('name')->nullable()->comment('Название приказа');
            $table->string('numorder')->nullable();
            $table->timestamp('dateorder')->nullable();
            $table->string('note')->nullable();
            $table->integer('isactualorder');
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
        Schema::dropIfExists('bk_orders');
    }
};
