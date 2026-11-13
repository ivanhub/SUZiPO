<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MatrixImportService
{
    protected array $sheetTableMap = [
        'ПО'    => 'matrix_po',
        'ДПО'   => 'matrix_dpo',
        'Курсы' => 'matrix_courses',
        'ОТ'    => 'matrix_ot',
    ];

    public function import(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Загруженный файл не найден на сервере.");
        }

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        $stats = [];

        DB::beginTransaction();

        try {
            foreach ($this->sheetTableMap as $sheetName => $tableName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);

                if (!$sheet) {
                    Log::warning("Лист '{$sheetName}' не найден в файле Excel.");
                    $stats[$sheetName] = ['status' => 'not_found', 'created' => 0, 'updated' => 0];
                    continue;
                }

                if (!Schema::hasTable($tableName)) {
                    throw new \Exception("Таблица '{$tableName}' отсутствует в базе данных.");
                }

                $stats[$sheetName] = $this->processSheet($sheet, $tableName, $sheetName);
            }

            DB::commit();
            return $stats;

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Ошибка импорта матриц из Excel: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    protected function processSheet($sheet, string $tableName, string $sheetName): array
    {
        $highestRow = $sheet->getHighestDataRow();
        $tableColumns = Schema::getColumnListing($tableName);

        $created = 0;
        $updated = 0;

        for ($row = 4; $row <= $highestRow; $row++) {
            $rowNumVal = trim((string)$sheet->getCell("A{$row}")->getValue());
            $codeVal   = trim((string)$sheet->getCell("D{$row}")->getValue());

            if ($rowNumVal === '' && $codeVal === '') {
                break;
            }

            if ($codeVal === '') {
                continue;
            }

            //     $rawData = [
            //     'number'                       => is_numeric($rowNumVal) ? (int)$rowNumVal : null,
            //     'sro_education_type'           => $this->cleanString($sheet->getCell("B{$row}")->getValue()),
            //     'registry_number'              => $this->cleanString($sheet->getCell("C{$row}")->getValue()),
            //     'code'                         => $codeVal,
            //     'additional_code'              => $this->cleanString($sheet->getCell("E{$row}")->getValue()),
            //     'date_start'                   => $this->parseExcelDate($sheet->getCell("F{$row}")->getValue()),
            //     'date_end'                     => $this->parseExcelDate($sheet->getCell("G{$row}")->getValue()),
            //     'education_type'               => $this->cleanString($sheet->getCell("H{$row}")->getValue()),
            //     'profession_name'              => $this->cleanString($sheet->getCell("I{$row}")->getValue()),
            //     'rank'                         => $this->cleanString($sheet->getCell("J{$row}")->getValue()),
            //     'full_name'                    => $this->cleanString($sheet->getCell("K{$row}")->getValue()),
            //     'study_form'                   => $this->cleanString($sheet->getCell("L{$row}")->getValue()),

            //     // Учебная нагрузка (родные колонки + новая колонка ДОТ практики)
            //     'hours'                        => $this->parseInt($sheet->getCell("M{$row}")->getValue()),
            //     'theory_hours'                 => $this->parseInt($sheet->getCell("N{$row}")->getValue()),
            //     'self_study_hours'             => $this->parseInt($sheet->getCell("O{$row}")->getValue()),
            //     'practical_hours'              => $this->parseInt($sheet->getCell("P{$row}")->getValue()),
            //     'practice_hours'               => $this->parseInt($sheet->getCell("Q{$row}")->getValue()),
            //     'distance_practical_hours'     => $this->parseNumber($sheet->getCell("R{$row}")->getValue()),

            //     // Стоимость обучения (руб.)
            //     'cost_total'                   => $this->parseNumber($sheet->getCell("S{$row}")->getValue()),
            //     'cost_fulltime_theory'         => $this->parseNumber($sheet->getCell("T{$row}")->getValue()),
            //     'cost_distance_theory'         => $this->parseNumber($sheet->getCell("U{$row}")->getValue()),
            //     'cost_industrial_practice'     => $this->parseNumber($sheet->getCell("V{$row}")->getValue()),
            //     'cost_fulltime_practice'       => $this->parseNumber($sheet->getCell("W{$row}")->getValue()),
            //     'cost_distance_practice'       => $this->parseNumber($sheet->getCell("X{$row}")->getValue()),

            //     // Организационные параметры (родные колонки + новые из миграции)
            //     'listener_category'            => $this->cleanString($sheet->getCell("Y{$row}")->getValue()),
            //     'group_size'                   => $this->cleanString($sheet->getCell("Z{$row}")->getValue()),
            //     'control_form'                 => $this->cleanString($sheet->getCell("AA{$row}")->getValue()),
            //     'commission_type'              => $this->cleanString($sheet->getCell("AB{$row}")->getValue()),
            //     'document_type'                => $this->cleanString($sheet->getCell("AC{$row}")->getValue()),
            //     'periodicity'                  => $this->cleanString($sheet->getCell("AD{$row}")->getValue()),
            //     'notes'                        => $this->cleanString($sheet->getCell("AE{$row}")->getValue()),
            //     'uchipro'                      => $this->cleanString($sheet->getCell("AF{$row}")->getValue()),
            //     'info_system'                  => $this->cleanString($sheet->getCell("AG{$row}")->getValue()),
            //     'teacher_requirements'         => $this->cleanString($sheet->getCell("AH{$row}")->getValue()),
            //     'equipment'                    => $this->cleanString($sheet->getCell("AI{$row}")->getValue()),
            //     'equipment_location'           => $this->cleanString($sheet->getCell("AJ{$row}")->getValue()),
            //     'teacher_fio'                  => $this->cleanString($sheet->getCell("AK{$row}")->getValue()),
            //     'only_yung'                    => $this->cleanString($sheet->getCell("AL{$row}")->getValue()),
            //     'og'                           => $this->cleanString($sheet->getCell("AM{$row}")->getValue()),
            //     'third_party'                  => $this->cleanString($sheet->getCell("AN{$row}")->getValue()),
            //     'site_publication'             => $this->cleanString($sheet->getCell("AO{$row}")->getValue()),
            //     'inf_mo'                       => $this->cleanString($sheet->getCell("AP{$row}")->getValue()),
            // ];
            $studyFormRaw = $this->cleanString($sheet->getCell("L{$row}")->getValue());
            $studyFormNormalized = $studyFormRaw ? preg_replace('/\s+/u', ' ', $studyFormRaw) : null;
            $isIntHoursTable = in_array($tableName, ['matrix_po', 'matrix_courses']);
            $parseHour = fn($val) => $isIntHoursTable ? $this->parseInt($val) : $this->parseNumber($val);

            $rawData = [
                // 1. № п/п (Колонка A)
                'number'                       => is_numeric($rowNumVal) ? (int)$rowNumVal : null,
                'row_number'                   => is_numeric($rowNumVal) ? (int)$rowNumVal : null,

                // 2. Новые поля идентификации из миграции (Колонки B, C, E, F, G)
                'sro_education_type'           => $this->cleanString($sheet->getCell("B{$row}")->getValue()),
                'registry_number'              => $this->cleanString($sheet->getCell("C{$row}")->getValue()),
                'code'                         => $codeVal,
                'additional_code'              => $this->cleanString($sheet->getCell("E{$row}")->getValue()),
                'date_start'                   => $this->parseExcelDate($sheet->getCell("F{$row}")->getValue()),
                'date_end'                     => $this->parseExcelDate($sheet->getCell("G{$row}")->getValue()),

                // 3. Вид обучения (Колонка H)
                'education_type'               => $this->cleanString($sheet->getCell("H{$row}")->getValue()),
                'dpo_type'                     => $this->cleanString($sheet->getCell("H{$row}")->getValue()),
                'training_type'                => $this->cleanString($sheet->getCell("H{$row}")->getValue()),

                // 4. Наименование рабочей профессии / программы (Колонка I)
                'profession_name'              => $this->cleanString($sheet->getCell("I{$row}")->getValue()),
                'program_name'                 => $this->cleanString($sheet->getCell("I{$row}")->getValue()),

                // 5. Разряд / квалификация (Колонка J)
                'rank'                         => $this->cleanString($sheet->getCell("J{$row}")->getValue()),
                'qualification_rank'           => $this->cleanString($sheet->getCell("J{$row}")->getValue()),

                // 6. Полное наименование программы и Форма обучения (Колонки K, L)
                'full_name'                    => $this->cleanString($sheet->getCell("K{$row}")->getValue()),
                'study_form'                   => $studyFormNormalized,

                // 7. Учебная нагрузка (Колонки M, N, O, P, Q, R)
                'hours'                        => $parseHour($sheet->getCell("M{$row}")->getValue()),
                'total_hours'                  => $parseHour($sheet->getCell("M{$row}")->getValue()),

                'theory_hours'                 => $parseHour($sheet->getCell("N{$row}")->getValue()),
                'theoretical_hours'            => $parseHour($sheet->getCell("N{$row}")->getValue()),
                'fulltime_theoretical_hours'   => $parseHour($sheet->getCell("N{$row}")->getValue()),

                'self_study_hours'             => $parseHour($sheet->getCell("O{$row}")->getValue()),
                'distance_theoretical_hours'   => $parseHour($sheet->getCell("O{$row}")->getValue()),

                'practical_hours'              => $parseHour($sheet->getCell("P{$row}")->getValue()),
                'industrial_practice_hours'    => $parseHour($sheet->getCell("P{$row}")->getValue()),

                'practice_hours'               => $parseHour($sheet->getCell("Q{$row}")->getValue()),
                'distance_practical_hours'     => $this->parseNumber($sheet->getCell("R{$row}")->getValue()),

                // 8. Стоимость обучения из миграции (Колонки S, T, U, V, W, X)
                'cost_total'                   => $this->parseNumber($sheet->getCell("S{$row}")->getCalculatedValue()),
                'cost_fulltime_theory'         => $this->parseNumber($sheet->getCell("T{$row}")->getCalculatedValue()),
                'cost_distance_theory'         => $this->parseNumber($sheet->getCell("U{$row}")->getCalculatedValue()),
                'cost_industrial_practice'     => $this->parseNumber($sheet->getCell("V{$row}")->getCalculatedValue()),
                'cost_fulltime_practice'       => $this->parseNumber($sheet->getCell("W{$row}")->getCalculatedValue()),
                'cost_distance_practice'       => $this->parseNumber($sheet->getCell("X{$row}")->getCalculatedValue()),

                // 9. Категория, наполняемость, аттестация, комиссия, документ (Колонки Y, Z, AA, AB, AC)
                'listener_category'            => $this->cleanString($sheet->getCell("Y{$row}")->getValue()),
                'student_category'             => $this->cleanString($sheet->getCell("Y{$row}")->getValue()),

                'group_size'                   => $this->cleanString($sheet->getCell("Z{$row}")->getValue()),
                'group_capacity'               => $this->cleanString($sheet->getCell("Z{$row}")->getValue()),

                'control_form'                 => $this->cleanString($sheet->getCell("AA{$row}")->getValue()),
                'attestation_form'             => $this->cleanString($sheet->getCell("AA{$row}")->getValue()),

                'commission_type'              => $this->cleanString($sheet->getCell("AB{$row}")->getValue()),

                'document_type'                => $this->cleanString($sheet->getCell("AC{$row}")->getValue()),
                'issued_document'              => $this->cleanString($sheet->getCell("AC{$row}")->getValue()),

                // 10. Периодичность, примечание, платформа, ИС (Колонки AD, AE, AF, AG)
                'periodicity'                  => $this->cleanString($sheet->getCell("AD{$row}")->getValue()),

                'notes'                        => $this->cleanString($sheet->getCell("AE{$row}")->getValue()),
                'note'                         => $this->cleanString($sheet->getCell("AE{$row}")->getValue()),

                'uchipro'                      => $this->cleanString($sheet->getCell("AF{$row}")->getValue()),
                'uchi_pro'                     => $this->cleanString($sheet->getCell("AF{$row}")->getValue()),

                'info_system'                  => $this->cleanString($sheet->getCell("AG{$row}")->getValue()),
                'information_system_entry'     => $this->cleanString($sheet->getCell("AG{$row}")->getValue()),

                // 11. Требования, оборудование, преподаватель (Колонки AH, AI, AJ, AK)
                'teacher_requirements'         => $this->cleanString($sheet->getCell("AH{$row}")->getValue()),
                'equipment'                    => $this->cleanString($sheet->getCell("AI{$row}")->getValue()),
                'equipment_location'           => $this->cleanString($sheet->getCell("AJ{$row}")->getValue()),

                'teacher_fio'                  => $this->cleanString($sheet->getCell("AK{$row}")->getValue()),
                'teacher_name'                 => $this->cleanString($sheet->getCell("AK{$row}")->getValue()),

                // 12. Флаги заказчиков и публикации из миграции (Колонки AL, AM, AN, AO, AP)
                'only_yung'                    => $this->cleanString($sheet->getCell("AL{$row}")->getValue()),
                'og'                           => $this->cleanString($sheet->getCell("AM{$row}")->getValue()),
                'third_party'                  => $this->cleanString($sheet->getCell("AN{$row}")->getValue()),
                'site_publication'             => $this->cleanString($sheet->getCell("AO{$row}")->getValue()),
                'inf_mo'                       => $this->cleanString($sheet->getCell("AP{$row}")->getValue()),
            ];

            if ($sheetName === 'ОТ') {
                $rawData['code_ucjung'] = $this->cleanString($sheet->getCell("AQ{$row}")->getValue());
                $rawData['code_ul']     = $this->cleanString($sheet->getCell("AR{$row}")->getValue());
            }

            // if ($sheetName === 'ОТ') {
            //     $rawData['kod_uc'] = $this->cleanString($sheet->getCell("AQ{$row}")->getValue());
            //     $rawData['kod_ul'] = $this->cleanString($sheet->getCell("AR{$row}")->getValue());
            // }

            $filteredData = array_intersect_key($rawData, array_flip($tableColumns));

            // Составной ключ поиска по вашим родным колонкам (защита разных форм одного шифра)
            $potentialKeys = ['code', 'study_form', 'theory_hours', 'self_study_hours'];
            $matchCriteria = [];
            foreach ($potentialKeys as $key) {
                if (in_array($key, $tableColumns) && array_key_exists($key, $filteredData)) {
                    $matchCriteria[$key] = $filteredData[$key];
                }
            }

            $existing = DB::table($tableName)->where($matchCriteria)->first();

            if ($existing) {
                if (in_array('updated_at', $tableColumns)) {
                    $filteredData['updated_at'] = now();
                }
                
                DB::table($tableName)->where('id', $existing->id)->update($filteredData);
                $updated++;
            } else {
                if (in_array('created_at', $tableColumns)) {
                    $filteredData['created_at'] = now();
                }
                if (in_array('updated_at', $tableColumns)) {
                    $filteredData['updated_at'] = now();
                }
                DB::table($tableName)->insert($filteredData);
                $created++;
            }
            
        }

        return [
            'status'  => 'ok',
            'created' => $created,
            'updated' => $updated,
        ];
    }

    protected function cleanString($value): ?string
    {
        if ($value === null) {
            return null;
        }
        $str = trim((string)$value);
        return $str === '' ? null : $str;
    }

    protected function parseNumber($value): ?float
    {
        if ($value === null || trim((string)$value) === '') {
            return null;
        }
        $cleaned = str_replace([' ', ','], ['', '.'], trim((string)$value));
        return is_numeric($cleaned) ? (float)$cleaned : null;
    }

    protected function parseInt($value): ?int
    {
        $num = $this->parseNumber($value);
        return $num !== null ? (int)round($num) : null;
    }


    protected function parseExcelDate($value): ?string
    {
        if ($value === null || trim((string)$value) === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float)$value))->format('Y-m-d');
            }
            return Carbon::parse(trim((string)$value))->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
