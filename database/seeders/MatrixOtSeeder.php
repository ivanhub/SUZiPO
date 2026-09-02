<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MatrixOtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Путь к JSON файлу. Убедитесь, что папка database/data существует.
        $jsonFilePath = database_path('data/matrix_ot.json');

        // Проверяем существование файла
        if (!File::exists($jsonFilePath)) {
            $this->command->error("Файл ot_data.json не найден по пути: {$jsonFilePath}");
            return;
        }

        // Читаем содержимое файла и декодируем JSON в массив
        $jsonContent = File::get($jsonFilePath);
        $data = json_decode($jsonContent, true);

        // Проверяем, что данные получены и не пусты
        if (is_null($data) || empty($data)) {
            $this->command->error("Файл ot_data.json пуст или содержит некорректный JSON.");
            return;
        }

        // Указываем имя таблицы
        $tableName = 'matrix_ot';

        // Очищаем таблицу перед заполнением (опционально, чтобы избежать дубликатов)
        DB::table($tableName)->truncate();

        // Вставляем данные в базу
        // Рекомендуется использовать chunk для больших объемов данных
        foreach (array_chunk($data, 500) as $chunk) {
            DB::table($tableName)->insert($chunk);
        }

        $this->command->info("Данные из ot_data.json успешно добавлены в таблицу {$tableName}.");
    }
}