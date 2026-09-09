<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            // Поля из all_users_sap (сохраняем копию)
            $table->string('tab_number', 50)->nullable()->after('user_sap_id');
            $table->date('birth_date')->nullable()->after('middle_name');
            $table->string('gender', 10)->nullable()->after('birth_date');
            $table->string('gender_key', 20)->nullable()->after('gender');
            $table->string('pfr_certificate', 50)->nullable()->after('gender_key');
            $table->string('position', 255)->nullable()->after('pfr_certificate');
            $table->string('rank', 50)->nullable()->after('position');
            $table->string('level_4_name', 500)->nullable()->after('rank');
            $table->string('level_3_name', 500)->nullable()->after('level_4_name');
            $table->string('duv_b', 50)->nullable()->after('level_3_name');
            $table->string('mvz', 50)->nullable()->after('duv_b');
            $table->string('employee_category', 100)->nullable()->after('mvz');
            
            $table->index('tab_number');
        });
    }

    public function down(): void
    {
        Schema::table('request_employees', function (Blueprint $table) {
            $table->dropIndex(['tab_number']);
            $table->dropColumn([
                'tab_number', 'birth_date', 'gender', 'gender_key',
                'pfr_certificate', 'position', 'rank', 'level_4_name',
                'level_3_name', 'duv_b', 'mvz', 'employee_category'
            ]);
        });
    }
};