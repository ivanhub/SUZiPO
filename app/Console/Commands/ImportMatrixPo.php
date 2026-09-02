<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MatrixPo;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMatrixPo extends Command
{
    protected $signature = 'import:matrix-po {file}';
    protected $description = 'Импорт матрицы ПО (программ обучения) из Excel';

    public function handle()
    {
        $file = $this->argument('file');
        
        if (!file_exists($file)) {
            $this->error("Файл {$file} не найден!");
            return;
        }
        
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        $count = 0;
        
        // Пропускаем заголовки (первые 3 строки)
        for ($i = 3; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Пропускаем пустые строки
            if (empty($row[0]) && empty($row[1])) continue;
            
            $matrixPo = [
                'number' => $this->cleanInt($row[0] ?? null),
                'code' => $this->cleanString($row[1] ?? null),
                'education_type' => $this->cleanString($row[2] ?? null),
                'profession_name' => $this->cleanString($row[3] ?? null),
                'rank' => $this->cleanString($row[4] ?? null),
                'full_name' => $this->cleanString($row[5] ?? null),
                'study_form' => $this->cleanString($row[6] ?? null),
                'hours' => $this->cleanInt($row[7] ?? null),
                'theory_hours' => $this->cleanInt($row[8] ?? null),
                'self_study_hours' => $this->cleanInt($row[9] ?? null),
                'practical_hours' => $this->cleanInt($row[10] ?? null),
                'practice_hours' => $this->cleanInt($row[11] ?? null),
                'listener_category' => $this->cleanString($row[12] ?? null),
                'group_size' => $this->cleanString($row[13] ?? null),
                'control_form' => $this->cleanString($row[14] ?? null),
                'commission_type' => $this->cleanString($row[15] ?? null),
                'document_type' => $this->cleanString($row[16] ?? null),
                'notes' => $this->cleanString($row[17] ?? null),
                'uchipro' => $this->cleanString($row[18] ?? null),
                'info_system' => $this->cleanString($row[19] ?? null),
                'teacher_requirements' => $this->cleanString($row[20] ?? null),
                'equipment' => $this->cleanString($row[21] ?? null),
                'equipment_location' => $this->cleanString($row[22] ?? null),
                'teacher_fio' => $this->cleanString($row[23] ?? null),
            ];
            
            MatrixPo::create($matrixPo);
            $count++;
        }
        
        $this->info("Импортировано: {$count} записей из файла {$file}");
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