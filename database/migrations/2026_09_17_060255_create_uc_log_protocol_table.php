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
        Schema::create('uc_log_protocol', function (Blueprint $table) {
            $table->string('id_log_protocol', 36)->nullable();
            $table->string('id_protocol', 36)->nullable();
            $table->string('id_typeofeducprogram', 36)->nullable();
            $table->string('numberprotocol', 50)->nullable();
            $table->date('dateprotocol');
            $table->string('numberorder', 50)->nullable();
            $table->date('dateorder');
            $table->date('begindate');
            $table->date('enddate');
            $table->date('begindatefact')->nullable();
            $table->string('formeduc', 50)->nullable();
            $table->string('scan')->nullable();
            $table->string('flageisot', 50)->nullable();
            $table->string('flagapproved', 50)->nullable();
            $table->string('notecomission')->nullable();
            $table->string('note', 50)->nullable();
            $table->string('linkjournal')->nullable();
            $table->string('inworkdomenname', 50)->nullable();
            $table->string('notepr')->nullable();
            $table->date('dateapproved')->nullable();
            $table->date('datefisfrdo')->nullable();
            $table->date('datemintrud')->nullable();
            $table->timestamp('datechange');
            $table->string('Login', 50)->nullable();
            $table->string('notelog', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uc_log_protocol');
    }
};
