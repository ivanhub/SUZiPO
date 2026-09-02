<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MatrixDpoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Путь к JSON файлу. Создайте папку database/data и поместите туда файл data.json
        $jsonFilePath = database_path('data/matrix_dpo.json');

        // Проверяем существование файла
        if (!File::exists($jsonFilePath)) {
            $this->command->error("Файл data.json не найден по пути: {$jsonFilePath}");
            return;
        }

        // Читаем содержимое файла и декодируем JSON в массив
        $jsonContent = File::get($jsonFilePath);
        $data = json_decode($jsonContent, true);

        // Проверяем, что данные получены и не пусты
        if (is_null($data) || empty($data)) {
            $this->command->error("Файл data.json пуст или содержит некорректный JSON.");
            return;
        }

        // Указываем имя таблицы
        $tableName = 'matrix_dpo';

        // Очищаем таблицу перед заполнением (опционально, чтобы избежать дубликатов)
        DB::table($tableName)->truncate();

        // Вставляем данные в базу
        // Важно: если данные будут вставляться пачками, нужно учитывать ограничения на размер запроса.
        // В данном случае мы вставляем все сразу. Для больших массивов используйте chunk().
        DB::table($tableName)->insert($data);

        $this->command->info("Данные из data.json успешно добавлены в таблицу {$tableName}.");
    }
}