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
        Schema::create('bk_sbi_direct_educ', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('id_bk')->nullable();
            $table->string('name_direct', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bk_sbi_direct_educ');
    }
};
