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
        Schema::create('srv_globalapplicationsetting', function (Blueprint $table) {
            $table->integer('id');
            $table->string('settingname', 100)->nullable();
            $table->string('settingvalue', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv_globalapplicationsetting');
    }
};
