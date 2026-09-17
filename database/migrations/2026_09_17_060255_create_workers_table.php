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
        Schema::create('workers', function (Blueprint $table) {
            $table->integer('worker_id');
            $table->integer('worker_import_id')->nullable();
            $table->integer('enterprise_id')->nullable();
            $table->integer('ent_department_id')->nullable();
            $table->integer('dep_appointment_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('category_site_id')->nullable();
            $table->string('worker_last_name', 100)->nullable();
            $table->string('worker_first_name', 100)->nullable();
            $table->string('worker_middle_name', 100)->nullable();
            $table->binary('worker_image')->nullable();
            $table->string('worker_image_filename')->nullable();
            $table->integer('worker_image_filesize')->nullable();
            $table->string('worker_image_file_ext', 3)->nullable();
            $table->timestamp('worker_birth_day')->nullable();
            $table->string('worker_sex', 1)->nullable();
            $table->string('worker_phone', 100)->nullable();
            $table->string('worker_fax', 50)->nullable();
            $table->string('worker_www')->nullable();
            $table->string('worker_email', 100)->nullable();
            $table->integer('worker_publ')->nullable();
            $table->binary('worker_publ_image')->nullable();
            $table->string('worker_publ_image_ext', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
