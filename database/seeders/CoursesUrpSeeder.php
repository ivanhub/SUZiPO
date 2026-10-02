<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CoursesUrp;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

final class CoursesUrpSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeds/excel/Справочник курсов (УРП в сторонних УЦ).xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("Файл не найден: {$filePath}");
            return;
        }

        $this->command->info("Начинаем импорт курсов...");
        
        CoursesUrp::truncate();

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getSheetByName('Лист2');
        
        if ($sheet === null) {
            $sheet = $spreadsheet->getActiveSheet();
        }

        $rows = $sheet->toArray();
        $courses = [];
        $skipCount = 0;
        $importCount = 0;
        $duplicateCount = 0;
        $seen = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $code = trim((string)($row[2] ?? ''));
            $name = trim((string)($row[3] ?? ''));

            if ($name === '' || $name === '0') {
                $skipCount++;
                continue;
            }

            $key = mb_strtolower($name);
            if (isset($seen[$key])) {
                $duplicateCount++;
                continue;
            }
            $seen[$key] = true;

            $courses[] = [
                'code' => $code ?: null,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $importCount++;
        }

        foreach (array_chunk($courses, 500) as $chunk) {
            CoursesUrp::insert($chunk);
        }

        $this->command->info("Импорт завершен!");
        $this->command->info("Импортировано: {$importCount}");
        $this->command->info("Пропущено: {$skipCount}");
        $this->command->info("Дублей: {$duplicateCount}");

        $spreadsheet->disconnectWorksheets();
    }
}