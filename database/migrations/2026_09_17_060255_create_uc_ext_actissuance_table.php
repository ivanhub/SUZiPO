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
        Schema::create('uc_ext_actissuance', function (Blueprint $table) {
            $table->string('id_actissuance', 36)->nullable();
            $table->string('fiowho')->nullable();
            $table->string('snilswho', 50)->nullable();
            $table->string('companywho')->nullable();
            $table->string('staffwho')->nullable();
            $table->string('managementwho')->nullable();
            $table->string('departmentwho')->nullable();
            $table->date('dateofissue')->nullable();
            $table->string('flagissued', 50)->nullable();
            $table->string('note')->nullable();
            $table->string('whomissued', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_ext_actissuance');
    }
};
