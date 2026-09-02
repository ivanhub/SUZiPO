<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MatrixCourse;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMatrixCourses extends Command
{
    protected $signature = 'import:matrix-courses {file}';
    protected $description = 'Импорт матрицы курсов из Excel';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error('Файл не найден!');
            return;
        }
        
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        $currentCategory = '';
        $count = 0;
        
        foreach ($rows as $index => $row) {
            // Пропускаем заголовки (первые 3 строки)
            if ($index < 3) continue;
            
            // Проверяем, является ли строка заголовком категории
            if (isset($row[11]) && preg_match('/^[IVX]+\./', (string)$row[11])) {
                $currentCategory = $this->cleanString($row[11]);
                continue;
            }
            
            // Пропускаем пустые строки
            if (empty($row[0]) && empty($row[1])) continue;
            
            $course = [
                'program' => $currentCategory,
                'number' => $this->cleanInt($row[0]),
                'code' => $this->cleanString($row[1]),
                'education_type' => $this->cleanString($row[2]),
                'program_name' => $this->cleanString($row[3]),
                'full_name' => $this->cleanString($row[4]),
                'study_form' => $this->cleanString($row[5]),
                'hours' => $this->cleanInt($row[6]),
                'theory_hours' => $this->cleanInt($row[7]),
                'self_study_hours' => $this->cleanInt($row[8]),
                'practical_hours' => $this->cleanInt($row[9]),
                'practice_hours' => $this->cleanInt($row[10]),
                'listener_category' => $this->cleanString($row[11]),
                'group_size' => $this->cleanString($row[12]),
                'control_form' => $this->cleanString($row[13]),
                'commission_type' => $this->cleanString($row[14]),
                'document_type' => $this->cleanString($row[15]),
                'notes' => $this->cleanString($row[16]),
                'uchipro' => $this->cleanString($row[17]),
                'info_system' => $this->cleanString($row[18]),
                'teacher_requirements' => $this->cleanString($row[19]),
                'equipment' => $this->cleanString($row[20]),
                'equipment_location' => $this->cleanString($row[21]),
                'teacher_fio' => $this->cleanString($row[22]),
            ];
            
            MatrixCourse::create($course);
            $count++;
        }
        
        $this->info("Импортировано: {$count} записей");
    }
    
    /**
     * Очистка строковых полей
     */
    private function cleanString($value)
    {
        if ($value === null) return null;
        
        $value = trim((string)$value);
        
        // Заменяем "-" и пустые значения на null
        if ($value === '-' || $value === '' || $value === '—') {
            return null;
        }
        
        return $value;
    }
    
    /**
     * Очистка целочисленных полей
     */
    private function cleanInt($value)
    {
        if ($value === null) return null;
        
        $value = trim((string)$value);
        
        // Заменяем "-" и пустые значения на null
        if ($value === '-' || $value === '' || $value === '—') {
            return null;
        }
        
        // Проверяем, является ли значение числом
        if (!is_numeric($value)) {
            return null;
        }
        
        return (int)$value;
    }
}