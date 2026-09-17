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
        Schema::create('app_teachingschedule', function (Blueprint $table) {
            $table->integer('idteachingschedule');
            $table->integer('iddemand');
            $table->integer('idclassroom');
            $table->integer('idcurator');
            $table->integer('idteacher');
            $table->timestamp('startdate');
            $table->timestamp('enddate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_teachingschedule');
    }
};
