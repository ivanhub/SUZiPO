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
        Schema::create('protocols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('request_id')->nullable();
            $table->string('protocol_number', 100)->nullable();
            $table->date('date')->nullable();
            $table->string('status', 50)->default('draft')->index();
            $table->string('result')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};
