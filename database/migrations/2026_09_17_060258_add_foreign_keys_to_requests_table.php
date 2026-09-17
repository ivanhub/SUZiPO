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
        Schema::table('requests', function (Blueprint $table) {
            $table->foreign(['audience_id'])->references(['id'])->on('requests_audiences')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['city_id'])->references(['id'])->on('requests_cities')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['course_id'])->references(['id'])->on('requests_courses')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['curator_id'])->references(['id'])->on('requests_curators')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['discipline_id'])->references(['id'])->on('requests_disciplines')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['event_type_id'])->references(['id'])->on('requests_events_types')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['learn_reason_id'])->references(['id'])->on('requests_learn_reasons')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['learning_resource_id'])->references(['id'])->on('requests_learning_resources')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['learning_type_id'])->references(['id'])->on('requests_learning_types')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['profession_id'])->references(['id'])->on('requests_professions')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['provider_id'])->references(['id'])->on('requests_providers')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['teacher_id'])->references(['id'])->on('requests_teachers')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('requests_audience_id_foreign');
            $table->dropForeign('requests_city_id_foreign');
            $table->dropForeign('requests_course_id_foreign');
            $table->dropForeign('requests_curator_id_foreign');
            $table->dropForeign('requests_discipline_id_foreign');
            $table->dropForeign('requests_event_type_id_foreign');
            $table->dropForeign('requests_learn_reason_id_foreign');
            $table->dropForeign('requests_learning_resource_id_foreign');
            $table->dropForeign('requests_learning_type_id_foreign');
            $table->dropForeign('requests_profession_id_foreign');
            $table->dropForeign('requests_provider_id_foreign');
            $table->dropForeign('requests_teacher_id_foreign');
            $table->dropForeign('requests_user_id_foreign');
        });
    }
};
