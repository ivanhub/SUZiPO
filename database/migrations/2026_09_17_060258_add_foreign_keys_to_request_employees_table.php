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
        Schema::table('request_employees', function (Blueprint $table) {
            $table->foreign(['request_id'])->references(['id'])->on('requests')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_sap_id'])->references(['id'])->on('all_users_sap')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            $table->dropForeign('request_employees_request_id_foreign');
            $table->dropForeign('request_employees_user_sap_id_foreign');
        });
    }
};
