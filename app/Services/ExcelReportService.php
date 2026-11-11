<?php

namespace App\Services;

use App\Models\AppProtocol;
use App\Models\UcExtReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ExcelReportService
{
    public function generateProtocolReport($protocolId)
    {
        $protocol = AppProtocol::with(['demand.course', 'demand.learningType', 'demand.employees', 'demand.curator'])->findOrFail($protocolId);
        $report = UcExtReport::where('id_protocol', $protocolId)->first();
        $employees = collect($protocol->demand?->employees ?? []);
        $matrixNum = $protocol->demand?->matrix_num;

        $templatePath = storage_path('app/templates/protocol_blank.xlsx');
        if (!file_exists($templatePath)) {
            throw new \Exception('Файл шаблона не найден по пути: storage/app/templates/protocol_blank.xlsx');
        }

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($templatePath);

        $rawSavedResults = DB::table('uc_ext_studentsprotocol')->where('id_protocol', $protocolId)->get();
        $savedResults = collect();
        foreach ($rawSavedResults as $row) {
            $studentInfo = DB::table('uc_ext_students')->where('id_student', $row->id_students)->first();
            $key = $studentInfo?->snils ? preg_replace('/[^0-9]/', '', $studentInfo->snils) : ($studentInfo?->personnelnumber ?? null);
            if (empty($key)) {
                $key = (string)$row->id_students;
            }
            if (!empty($key)) {
                $savedResults->put($key, $row);
            }
        }

        $commissionRows = DB::table('uc_ext_commissionprotocol')->where('id_protocol', $protocolId)->orderBy('sortorder')->get();
        $chairmanFio = $commissionRows->where('note', 'Председатель')->first()?->fio ?? '—';
        $deputyFio   = $commissionRows->where('note', 'Заместитель председателя')->first()?->fio ?? '—';
        $membersList = $commissionRows->where('note', 'Член комиссии')->pluck('fio')->join(', ');

        // ==========================================
        // ЛИСТ 1: ОТЧЕТ
        // ==========================================
        $sheetReport = $spreadsheet->getSheetByName('Отчет');
        if ($sheetReport) {
            $sheetReport->setCellValue('C11', $report->specialist ?? '—');
            $sheetReport->setCellValue('C12', $protocol->order_num ?? '—');
            $sheetReport->setCellValue('E12', $protocol->order_date ? Carbon::parse($protocol->order_date)->format('d.m.Y') : '—');
            $sheetReport->setCellValue('C13', $protocol->prot_num ?? '—');
            $sheetReport->setCellValue('E13', $protocol->prot_date ? Carbon::parse($protocol->prot_date)->format('d.m.Y') : '—');
            $sheetReport->setCellValue('C14', $protocol->prot_num ?? '—');
            $sheetReport->setCellValue('C15', $protocol->demand?->audience_id ?? '—');

            $sheetReport->setCellValue('C17', $employees->where('position_type', 'Рабочий')->count());
            $sheetReport->setCellValue('C18', $employees->where('position_type', 'Руководитель')->count());
            $sheetReport->setCellValue('C19', $employees->where('position_type', 'Специалист')->count());

            $sheetReport->setCellValue('C20', $protocol->demand?->learningType?->name ?? 'Курсы');
            $sheetReport->setCellValue('C21', $protocol->demand?->education_form ?? 'Очное');
            $sheetReport->setCellValue('C22', 'Программа обучения: ' . ($protocol->demand?->course?->course ?? '—'));

            $sheetReport->setCellValue('C23', $protocol->date_start ? Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
            $sheetReport->setCellValue('C24', $protocol->date_end ? Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');
            $sheetReport->setCellValue('C26', $protocol->program_hours ?? 0);
            $sheetReport->setCellValue('C27', $protocol->theory_hours ?? 0);
            $sheetReport->setCellValue('C28', 0);
            $sheetReport->setCellValue('C29', $protocol->practice_hours ?? 0);
            $sheetReport->setCellValue('C31', 0);
            $sheetReport->setCellValue('C32', 0);

            $sheetReport->setCellValue('C33', $report ? $report->Show : $employees->count());
            $sheetReport->setCellValue('C34', $report ? $report->noshow : 0);
            $sheetReport->setCellValue('C35', $report ? $report->passed : $employees->count());
            $sheetReport->setCellValue('C36', $report ? $report->nopassed : 0);

            $sheetReport->setCellValue('C37', $report ? $report->countman : $employees->where('gender_key', '1')->count());
            $sheetReport->setCellValue('C38', $report ? $report->countwoman : $employees->where('gender_key', '2')->count());

            $sheetReport->setCellValue('C40', $employees->where('company_type', 'РН-ЮНГ')->count());
            $sheetReport->setCellValue('C41', $employees->where('company_type', 'Сервис')->count());
            $sheetReport->setCellValue('C42', $employees->where('company_type', 'Прочие')->count());
            $sheetReport->setCellValue('C43', $employees->where('company_type', 'Наличные')->count());

            $teacherFios = $protocol->demand?->teachers ? $protocol->demand->teachers->pluck('fio')->join(', ') : ($protocol->demand?->teacher?->fio ?? '—');
            $sheetReport->setCellValue('C44', $teacherFios);
            $sheetReport->setCellValue('C45', $report->specialist ?? '—');
            $sheetReport->setCellValue('C46', $protocol->demand?->curator?->fio ?? '—');
        }
        // ==========================================
        // ЛИСТ 2: СПИСОК
        // ==========================================
        $sheetList = $spreadsheet->getSheetByName('Список');
        if ($sheetList) {
            $sheetList->setCellValue('E6', ($protocol->order_num ?? '—'));
            $sheetList->setCellValue('C7', $protocol->demand?->course?->course ?? '—');
            $sheetList->setCellValue('C11', $protocol->prot_num ?? '—'); 
            $sheetList->setCellValue('D11', $report->specialist ?? '---'); 
            $sheetList->setCellValue('D12', $protocol->date_start ? Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
            $sheetList->setCellValue('D13', $protocol->date_end ? Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');

            $startRow = 17;
            foreach ($employees as $index => $employee) {
                $currentRow = $startRow + $index;

                if ($index >= 2) {
                    $sheetList->insertNewRowBefore($currentRow, 1);
                }

                $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
                if (empty($empFullName)) {
                    $empFullName = $employee->fio ?? $employee->name ?? '—';
                }

                $sheetList->setCellValue('B' . $currentRow, $index + 1); 
                $sheetList->setCellValue('C' . $currentRow, $employee->tab_number ?? $employee->tab_num ?? '—'); 
                $sheetList->setCellValue('D' . $currentRow, $empFullName); 
                $sheetList->setCellValue('E' . $currentRow, $employee->position ?? '—'); 
                $sheetList->setCellValue('F' . $currentRow, $employee->rank ?? '—'); 
                $sheetList->setCellValue('G' . $currentRow, $employee->company ?? $employee->level_3_name ?? '---'); 
                $sheetList->setCellValue('H' . $currentRow, $employee->department ?? $employee->level_4_name ?? '—'); 
                $sheetList->setCellValue('I' . $currentRow, $employee->process_management ?? '—'); 
                $sheetList->setCellValue('J' . $currentRow, $employee->phone ?? '—'); 

                $birthDate = $employee->birth_date ? Carbon::parse($employee->birth_date)->format('d.m.Y') : '—';
                $sheetList->setCellValue('K' . $currentRow, $birthDate); 
                $sheetList->setCellValue('L' . $currentRow, $employee->snils ?? $employee->pfr_certificate ?? '—'); // СНИЛС
                $sheetList->setCellValue('M' . $currentRow, $employee->gender ?? '—'); // Пол
            }
        }

        // ==========================================
        // ЛИСТ 3: ПРОТОКОЛ
        // ==========================================
        $sheetTitle = 'Протокол Курсы';
        if ($matrixNum == 4) {
            $sheetTitle = 'Протокол ОТ';
        } elseif ($matrixNum == 5) {
            $sheetTitle = 'Протокол Мультимедиа';
        }

        $sheetProt = $spreadsheet->getSheetByName($sheetTitle) ?? $spreadsheet->getSheet(2);
        if ($sheetProt) {
            $sheetProt->setCellValue('E2', $protocol->prot_num ?? '—');
            $sheetProt->setCellValue('H2', $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->translatedFormat('d F Y') : '—');
            $sheetProt->setCellValue('F4', $protocol->order_num ?? '—');
            $sheetProt->setCellValue('I4', $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->translatedFormat('d F Y') : '—');
            $sheetProt->setCellValue('E5', $chairmanFio);
            $sheetProt->setCellValue('E6', $membersList ?: '-');
            $sheetProt->setCellValue('E8', $protocol->prot_num ?? '—');
            $sheetProt->setCellValue('G8', $protocol->demand?->course?->course ?? '—');
            $sheetProt->setCellValue('F9', $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
            $sheetProt->setCellValue('M9', $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');
            $sheetProt->setCellValue('F10', $protocol->program_hours ?? 8);

            $baseRowProt = 20; 
            $totalEmployees = $employees->count();

            foreach ($employees as $index => $employee) {
                $currentRow = $baseRowProt + $index;

                if ($index > 0) {
                    $sheetProt->insertNewRowBefore($currentRow, 1);
                }

                foreach (range('A', 'N') as $column) {
                    $sourceStyle = $sheetProt->getStyle($column . 18);
                    $sheetProt->duplicateStyle($sourceStyle, $column . $currentRow);

                    $sheetProt->getStyle($column . $currentRow)->getFont()->setBold(false);

                    if (!in_array($column, ['C', 'D'])) {
                        $sheetProt->getStyle($column . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    } else {
                        $sheetProt->getStyle($column . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    }
                    $sheetProt->getStyle($column . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                }

                $sheetProt->getRowDimension($currentRow)->setRowHeight(-1);

                $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
                if (empty($empFullName)) {
                    $empFullName = $employee->fio ?? $employee->name ?? '—';
                }

                $dbRow = $savedResults->get((string)$employee->id) ?? $savedResults->get(preg_replace('/[^0-9]/', '', $employee->snils ?? ''));
                $finalMark = $dbRow?->markitog ?? 'зачет';
                $eisotNum = $dbRow?->regnumber ?? $dbRow?->documentnumber ?? '—';

                $sheetProt->setCellValue('A' . $currentRow, $index + 1); // № пп
                $sheetProt->getCell('B' . $currentRow)->setValueExplicit($employee->tab_number ?? $employee->tab_num ?? '—', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // № табельный
                $sheetProt->setCellValue('C' . $currentRow, $empFullName); // ФИО
                $sheetProt->setCellValue('D' . $currentRow, $employee->position ?? '—'); // Должность/профессия
                $sheetProt->setCellValue('E' . $currentRow, $employee->rank ?? '—'); // Разряд
                $sheetProt->setCellValue('F' . $currentRow, $finalMark); // Итоговый зачет
                $sheetProt->setCellValue('G' . $currentRow, ''); // Оценка за практику

                // Рег. номер ЕИСОТ для Охраны Труда
                if ($matrixNum == 4) {
                    $sheetProt->getCell('H' . $currentRow)->setValueExplicit($eisotNum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheetProt->setCellValue('H' . $currentRow, '');
                }

                $sheetProt->setCellValue('I' . $currentRow, $employee->company ?? $employee->level_3_name ?? '-'); // Предприятие
                $sheetProt->setCellValue('J' . $currentRow, $finalMark); // Результат тестирования
                $sheetProt->setCellValue('K' . $currentRow, ''); // Подпись

                $dateStartFact = $dbRow?->begindatefact ? \Carbon\Carbon::parse($dbRow->begindatefact)->format('d.m.Y') : ($protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
                $dateEndFact = $dbRow?->enddatefact ? \Carbon\Carbon::parse($dbRow->enddatefact)->format('d.m.Y') : ($protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');

                $sheetProt->setCellValue('L' . $currentRow, $dateStartFact); // Начало обучения (факт)
                $sheetProt->setCellValue('M' . $currentRow, $dateEndFact); // Окончание обучения (факт)
                $sheetProt->setCellValue('N' . $currentRow, (int)($dbRow?->counthourfact ?? $protocol->program_hours ?? 8)); // Фактически пройдено

                $sheetProt->getStyle('C' . $currentRow)->getAlignment()->setWrapText(true);
                $sheetProt->getStyle('I' . $currentRow)->getAlignment()->setWrapText(true);
            }

            $insertedRows = $totalEmployees > 2 ? $totalEmployees - 2 : 0;

            // TODO: Доделать состав комиссии
            $sheetProt->setCellValue('F' . (25 + $insertedRows), $chairmanFio);
            $sheetProt->setCellValue('F' . (29 + $insertedRows), 'Сидоров С.В.');
            $sheetProt->setCellValue('F' . (31 + $insertedRows), 'Логинова И.М.');
            $sheetProt->setCellValue('B' . (35 + $insertedRows), 'Исполнитель: ' . ($report->specialist ?? '—'));
        }
        return $spreadsheet;
    }
}
