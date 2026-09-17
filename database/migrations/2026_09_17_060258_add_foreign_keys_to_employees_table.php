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
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign(['profession_id'])->references(['id'])->on('professions')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['qualification_id'])->references(['id'])->on('qualifications')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign('employees_profession_id_foreign');
            $table->dropForeign('employees_qualification_id_foreign');
        });
    }
};
