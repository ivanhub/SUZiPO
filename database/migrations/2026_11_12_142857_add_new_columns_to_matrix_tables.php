<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['matrix_po', 'matrix_dpo', 'matrix_courses', 'matrix_ot'];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Вид обучения в СРО
                if (!Schema::hasColumn($tableName, 'sro_education_type')) {
                    $table->string('sro_education_type')->nullable();
                }
                // № Программы по реестру
                if (!Schema::hasColumn($tableName, 'registry_number')) {
                    $table->string('registry_number', 100)->nullable();
                }
                // Дополнительный код
                if (!Schema::hasColumn($tableName, 'additional_code')) {
                    $table->text('additional_code')->nullable();
                }
                // Дата введения в действие
                if (!Schema::hasColumn($tableName, 'date_start')) {
                    $table->date('date_start')->nullable();
                }
                // Дата окончания действия
                if (!Schema::hasColumn($tableName, 'date_end')) {
                    $table->date('date_end')->nullable();
                }
                // Практическое обучение с применением ЭО и ДОТ (часы)
                if (!Schema::hasColumn($tableName, 'distance_practical_hours')) {
                    $table->double('distance_practical_hours')->nullable();
                }
                // Стоимость обучения без НДС
                if (!Schema::hasColumn($tableName, 'cost_total')) {
                    $table->decimal('cost_total', 12, 2)->nullable();
                }
                // Стоимость. Очное теоретическое обучение
                if (!Schema::hasColumn($tableName, 'cost_fulltime_theory')) {
                    $table->decimal('cost_fulltime_theory', 12, 2)->nullable();
                }
                // Стоимость. Теоретическое обучение с ДОТ
                if (!Schema::hasColumn($tableName, 'cost_distance_theory')) {
                    $table->decimal('cost_distance_theory', 12, 2)->nullable();
                }
                // Стоимость. Производственно-практическое обучение
                if (!Schema::hasColumn($tableName, 'cost_industrial_practice')) {
                    $table->decimal('cost_industrial_practice', 12, 2)->nullable();
                }
                //
                if (!Schema::hasColumn($tableName, 'cost_fulltime_practice')) {
                    $table->decimal('cost_fulltime_practice', 12, 2)->nullable();
                }
                // Стоимость. Очное практическое обучение
                if (!Schema::hasColumn($tableName, 'cost_distance_practice')) {
                    $table->decimal('cost_distance_practice', 12, 2)->nullable();
                }
                // Периодичность обучения
                if (!Schema::hasColumn($tableName, 'periodicity')) {
                    $table->string('periodicity')->nullable();
                }
                // Только для ЮНГ
                if (!Schema::hasColumn($tableName, 'only_yung')) {
                    $table->string('only_yung', 100)->nullable();
                }
                // ОГ
                if (!Schema::hasColumn($tableName, 'og')) {
                    $table->string('og', 100)->nullable();
                }
                // Сторонние предприятия
                if (!Schema::hasColumn($tableName, 'third_party')) {
                    $table->string('third_party', 100)->nullable();
                }
                // Публикация на сайте
                if (!Schema::hasColumn($tableName, 'site_publication')) {
                    $table->string('site_publication', 100)->nullable();
                }
                // ИНФ МО
                if (!Schema::hasColumn($tableName, 'inf_mo')) {
                    $table->string('inf_mo', 100)->nullable();
                }
                // Добавляем колонку требований к преподавателям для matrix_ot
                if (!Schema::hasColumn($tableName, 'teacher_requirements')) {
                    $table->text('teacher_requirements')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = ['matrix_po', 'matrix_dpo', 'matrix_courses', 'matrix_ot'];
        $columns = [
            'sro_education_type', 'registry_number', 'additional_code',
            'date_start', 'date_end', 'distance_practical_hours',
            'cost_total', 'cost_fulltime_theory', 'cost_distance_theory',
            'cost_industrial_practice', 'cost_fulltime_practice', 'cost_distance_practice',
            'periodicity', 'only_yung', 'og', 'third_party', 'site_publication', 'inf_mo'
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                throw new \RuntimeException("Таблица '{$tableName}' не найдена в текущей БД (" . config('database.connections.' . config('database.default') . '.database') . ", схема: " . config('database.connections.' . config('database.default') . '.search_path') . ")");
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                foreach ($columns as $col) {
                    if (Schema::hasColumn($tableName, $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
