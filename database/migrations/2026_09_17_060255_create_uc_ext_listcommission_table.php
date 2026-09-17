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
        Schema::create('uc_ext_listcommission', function (Blueprint $table) {
            $table->string('id_listcommission', 36)->nullable();
            $table->string('fio')->nullable();
            $table->text('staff')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_listcommission');
    }
};
