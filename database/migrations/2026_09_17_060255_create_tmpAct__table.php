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
        Schema::create('tmpAct$', function (Blueprint $table) {
            $table->float('fullregnumber')->nullable();
            $table->string('fiowho')->nullable();
            $table->timestamp('dateofissue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmpAct$');
    }
};
