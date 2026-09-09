<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MatrixCourseSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/files/3.xlsx');
        
        if (!file_exists($file)) {
            $this->command->error("Файл {$file} не найден!");
            return;
        }
        
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        $currentCategory = '';
        $count = 0;
        
        // Пропускаем заголовки (первые 3 строки)
        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Проверяем, является ли строка заголовком категории
            if (isset($row[11]) && preg_match('/^[IVX]+\./', (string)$row[11])) {
                $currentCategory = $this->cleanString($row[11]);
                continue;
            }
            
            // Пропускаем пустые строки
            if (empty($row[0]) && empty($row[1])) continue;
            
            DB::table('matrix_courses')->insert([
                'program' => $currentCategory,
                'number' => $this->cleanInt($row[0] ?? null),
                'code' => $this->cleanString($row[1] ?? null),
                'education_type' => $this->cleanString($row[2] ?? null),
                'program_name' => $this->cleanString($row[3] ?? null),
                'full_name' => $this->cleanString($row[4] ?? null),
                'study_form' => $this->cleanString($row[5] ?? null),
                'hours' => $this->cleanInt($row[6] ?? null),
                'theory_hours' => $this->cleanInt($row[7] ?? null),
                'self_study_hours' => $this->cleanInt($row[8] ?? null),
                'practical_hours' => $this->cleanInt($row[9] ?? null),
                'practice_hours' => $this->cleanInt($row[10] ?? null),
                'listener_category' => $this->cleanString($row[11] ?? null),
                'group_size' => $this->cleanString($row[12] ?? null),
                'control_form' => $this->cleanString($row[13] ?? null),
                'commission_type' => $this->cleanString($row[14] ?? null),
                'document_type' => $this->cleanString($row[15] ?? null),
                'notes' => $this->cleanString($row[16] ?? null),
                'uchipro' => $this->cleanString($row[17] ?? null),
                'info_system' => $this->cleanString($row[18] ?? null),
                'teacher_requirements' => $this->cleanString($row[19] ?? null),
                'equipment' => $this->cleanString($row[20] ?? null),
                'equipment_location' => $this->cleanString($row[21] ?? null),
                'teacher_fio' => $this->cleanString($row[22] ?? null),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $count++;
        }
        
        $this->command->info("Сидер: импортировано {$count} записей из файла 2.xlsx");
    }
    
    private function cleanString($value): ?string
    {
        if ($value === null) return null;
        
        $value = trim((string)$value);
        
        if ($value === '-' || $value === '' || $value === '—' || $value === ' ') {
            return null;
        }
        
        return $value;
    }
    
    private function cleanInt($value): ?int
    {
        if ($value === null) return null;
        
        $value = trim((string)$value);
        
        if ($value === '-' || $value === '' || $value === '—' || $value === ' ') {
            return null;
        }
        
        if (!is_numeric($value)) {
            return null;
        }
        
        return (int)$value;
    }
}