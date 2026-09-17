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
        Schema::create('srv_notifitriggerhistory', function (Blueprint $table) {
            $table->integer('idmsg');
            $table->integer('isdem')->nullable();
            $table->integer('entryid')->nullable();
            $table->integer('oldstate')->nullable();
            $table->integer('newstate')->nullable();
            $table->integer('newusereditid')->nullable();
            $table->integer('oldusereditid')->nullable();
            $table->timestamp('olddatestart')->nullable();
            $table->timestamp('newdatestart')->nullable();
            $table->timestamp('olddateend')->nullable();
            $table->timestamp('newdateend')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srv_notifitriggerhistory');
    }
};
