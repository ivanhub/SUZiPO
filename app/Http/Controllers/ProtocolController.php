<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UcExtReport;
use App\Models\AppProtocol;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use \Maatwebsite\Excel\Facades\Excel;

class ProtocolController extends Controller
{
    public function index(Request $request)
    {
        $query = AppProtocol::with([
            'demand.course',
            'demand.provider',
            'demand.profession',
            'demand.teacher',
            'demand.audience',
            'demand.curator',
            'editor'
        ]);

        if ($request->filled('activeTab') && $request->activeTab !== 'all') {
            $query->where('prot_status', $request->activeTab);
        }

        if ($request->filled('searchId')) {
            $query->where('prot_id', $request->searchId);
        }

        if ($request->filled('searchStatus')) {
            $statusSearch = mb_strtolower(trim($request->searchStatus));
            $query->where(function ($q) use ($statusSearch) {
                $q->whereRaw('CAST(prot_status AS TEXT) ILIKE ?', ["%{$statusSearch}%"]);
                if (str_contains('в работе', $statusSearch) || str_contains('актив', $statusSearch)) {
                    $q->orWhere('prot_status', 1);
                } elseif (str_contains('черновик', $statusSearch)) {
                    $q->orWhere('prot_status', 0);
                } elseif (str_contains('завершен', $statusSearch) || str_contains('завершён', $statusSearch)) {
                    $q->orWhere('prot_status', 4);
                }
            });
        }

                if ($request->filled('searchProtNum')) {
            $this->whereCyrillicLike($query, 'prot_num', $request->searchProtNum);
        }

        if ($request->filled('searchEditor')) {
            $query->whereHas('editor', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'name', $request->searchEditor);
            });
        }

        if ($request->filled('searchCourse')) {
            $query->whereHas('demand.course', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'course', $request->searchCourse);
            });
        }

        if ($request->filled('searchDemNum')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'req_number', $request->searchDemNum);
            });
        }

        if ($request->filled('searchQual')) {
            $query->whereHas('demand.profession', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'profession', $request->searchQual);
            });
        }

        if ($request->filled('searchTeacher')) {
            $query->whereHas('demand.teacher', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'fio', $request->searchTeacher);
            });
        }

        if ($request->filled('searchClassroom')) {
            $query->whereHas('demand.audience', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'number', $request->searchClassroom);
            });
        }

        if ($request->filled('searchCurator')) {
            $query->whereHas('demand.curator', function ($q) use ($request) {
                $this->whereCyrillicLike($q, 'fio', $request->searchCurator);
            });
        }

        $perPage = (int)$request->input('per_page', 10);
        if (!in_array($perPage, [10, 15, 25, 50, 100], true)) {
            $perPage = 10;
        }

        $protocols = $query->paginate($perPage)->withQueryString();

        // $protocols = $query->orderBy('prot_id', 'desc')->paginate(15);

        $protocols->appends($request->all());

        $totalCount = AppProtocol::count();

        $counts = AppProtocol::select('prot_status', DB::raw('count(*) as total'))
            ->whereNotNull('prot_status')
            ->groupBy('prot_status')
            ->pluck('total', 'prot_status')
            ->toArray();

        if ($request->ajax()) {
            return view('protocols.partials.table-rows', compact('protocols'))->render();
        }

        $popupTeachers = \App\Models\RequestsTeachers::orderBy('fio')->get();
        $popupLearningTypes = \App\Models\RequestsLearningType::orderBy('name')->get();

        $jsonProtocols = collect($protocols->items())->map(function ($p) {
            return [
                'prot_id' => $p->prot_id,
                'prot_num' => $p->prot_num,
                'prot_status' => $p->prot_status,
                'teacher_id' => $p->demand?->teacher_id ?? null,
                'learning_type_id' => $p->demand?->learning_type_id ?? null,
                'date_end' => $p->date_end ? \Carbon\Carbon::parse($p->date_end)->format('Y-m-d') : null,
            ];
        })->toArray();

        return view('protocols.index', compact('protocols', 'totalCount', 'counts', 'popupTeachers', 'popupLearningTypes', 'jsonProtocols'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'group_num'      => 'required|string|max:50',  // Номер группы
            'order_num'      => 'nullable|string|max:100', // Номер приказа
            'order_date'     => 'nullable|date',           // Дата приказа
            'prot_date'      => 'nullable|date',           // Дата формирования
            'flagapproved'   => 'required|integer',        // Состояние/статус протокола
            'Cost'           => 'nullable|numeric',        // Цена за человека
            'costvat'        => 'nullable|numeric',        // НДС%
            'theoryhours'    => 'nullable|integer',        // Часы теории
            'practicehours'  => 'nullable|integer',        // Часы практики
            'hoursbyprogram' => 'nullable|integer',        // Часы по программе
            'typedoc_id'     => 'nullable|string|max:100', // Тип документа
        ]);


        $protocol = AppProtocol::findOrFail($id);

        $protocol->prot_num        = $request->input('group_num');
        $protocol->group_num       = $request->input('group_num');
        $protocol->order_num       = $request->input('order_num');
        $protocol->order_date      = $request->input('order_date');
        $protocol->prot_date       = $request->input('prot_date') ?? now()->format('Y-m-d');
        $protocol->prot_status     = $request->input('flagapproved');

        $protocol->Cost            = $request->input('Cost');
        $protocol->costvat         = $request->input('costvat');
        $protocol->theoryhours     = $request->input('theoryhours');
        $protocol->practicehours   = $request->input('practicehours');
        $protocol->hoursbyprogram  = $request->input('hoursbyprogram');
        $protocol->typedoc_id      = $request->input('typedoc_id');

        $protocol->id_user_edit    = auth()->id() ?? 1;
        $protocol->date_edit       = now();

        $protocol->save();

        return redirect()->route('protocols.index')->with('success', 'Протокол успешно обновлен!');
    }

    public function createReport($id)
    {
        $protocol = \App\Models\AppProtocol::with([
            'demand.course',
            'demand.provider',
            'demand.teacher',
            'demand.curator',
            'demand.profession',
            'demand.audience',
            'demand.educationType',
            'demand.learningType',
            'demand.employees'
        ])->findOrFail($id);

        $matrixNum = $protocol->demand?->matrix_num;
        $learningTypeName = $protocol->demand?->educationType?->name ?? 'курсы';

        $refCurators = DB::table('ref_curators')
            ->orderBy('fio')
            ->get();

        // Подгружаем справочник аудиторий для ручного выбора на вкладке "Отчет"
        $audiences = \App\Models\RequestsAudience::orderBy('number')->get();
        // Подгружаем справочник преподавателей для множественного выбора
        $refCurators = DB::table('ref_curators')->orderBy('fio')->get();
        $teachers = \App\Models\RequestsTeachers::orderBy('fio')->get();
        $report = \App\Models\UcExtReport::where('id_protocol', $id)->first();

        $rawSavedResults = DB::table('uc_ext_studentsprotocol')
            ->where('id_protocol', $id)
            ->get();

        $savedResults = collect();
        foreach ($rawSavedResults as $row) {
            $studentInfo = \Illuminate\Support\Facades\DB::table('uc_ext_students')
                ->where('id_student', $row->id_students)
                ->first();

            $key = null;

            if ($studentInfo && !empty($studentInfo->snils)) {
                $key = preg_replace('/[^0-9]/', '', $studentInfo->snils);
            }
            if (empty($key) && $studentInfo && !empty($studentInfo->personnelnumber)) {
                $key = (string)$studentInfo->personnelnumber;
            }
            if (empty($key)) {
                $matchedEmp = $protocol->demand?->employees->first(function ($emp) use ($row) {
                    $fio = trim(($emp->last_name ?? '') . ' ' . ($emp->first_name ?? '') . ' ' . ($emp->middle_name ?? ''));
                    return trim($fio) === trim($row->fiofact);
                });

                $key = $matchedEmp ? (string)$matchedEmp->id : (string)$row->id_students;
            }
            if (!empty($key)) {
                $savedResults->put($key, $row);
            }
        }

        $allComissionMembers = \App\Models\ComissionMember::orderBy('fio')->get();
        // $currentComission = \App\Models\ProtocolComission::where('protocol_id', $id)->get();
        $currentComission = DB::table('uc_ext_commissionprotocol')->where('id_protocol', $id)->get();

        $tabNumbers = $protocol->demand?->employees->pluck('tab_number')->filter()->toArray() ?? [];

        $sapUsers = DB::table('all_users_sap')
            ->whereIn('tab_number', $tabNumbers)
            ->get()
            ->keyBy('tab_number');

        // =========================================================================
        // АВТОМАТИЧЕСКИЙ ПОДБОР ЧАСОВ ИЗ МАТРИЦ (ПО, ДПО, КУРСЫ, ОТ)
        // =========================================================================
        $matrixHours = [
            'program_hours'  => 0,
            'theory_hours'   => 0,
            'prod_hours'     => 0,
            'practice_hours' => 0,
            'self_hours'     => 0,
            'matched_source' => null,
        ];

        try {
            $tableMap = [
                1 => 'matrix_dpo',
                2 => 'matrix_po',
                3 => 'matrix_courses',
                4 => 'matrix_ot',
            ];

            $targetTable = $tableMap[(int)$matrixNum] ?? null;

            // Резервное определение таблицы по тексту вида обучения, если matrix_num не задан
            if (!$targetTable) {
                $typeLower = mb_strtolower($learningTypeName);
                if (str_contains($typeLower, 'дпо') || str_contains($typeLower, 'дополнительн')) {
                    $targetTable = 'matrix_dpo';
                } elseif (str_contains($typeLower, 'профессиональн') || $typeLower === 'по') {
                    $targetTable = 'matrix_po';
                } elseif (str_contains($typeLower, 'охрана труда') || $typeLower === 'от') {
                    $targetTable = 'matrix_ot';
                } else {
                    $targetTable = 'matrix_courses';
                }
            }

            if ($targetTable && \Illuminate\Support\Facades\Schema::hasTable($targetTable)) {
                $courseObj     = $protocol->demand?->course;
                $professionObj = $protocol->demand?->profession;
                $searchCode    = trim((string)($courseObj?->code ?? $professionObj?->code ?? ''));
                $searchName    = trim((string)($courseObj?->course ?? $courseObj?->name ?? $professionObj?->name ?? ''));
                $searchForm    = trim((string)($protocol->demand?->learningType?->name ?? ''));

                $query = DB::table($targetTable);

                if ($searchCode !== '') {
                    $query->where('code', $searchCode);
                } elseif ($searchName !== '') {
                    $tableCols = \Illuminate\Support\Facades\Schema::getColumnListing($targetTable);
                    $query->where(function ($q) use ($searchName, $tableCols) {
                        $q->where('full_name', 'ILIKE', "%{$searchName}%");
                        if (in_array('program_name', $tableCols)) {
                            $q->orWhere('program_name', 'ILIKE', "%{$searchName}%");
                        }
                        if (in_array('profession_name', $tableCols)) {
                            $q->orWhere('profession_name', 'ILIKE', "%{$searchName}%");
                        }
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }

                $candidates = $query->get();

                if ($candidates->isNotEmpty()) {
                    // Если у шифра несколько вариантов (очная/заочная), ищем совпадение по форме обучения
                    $matchedRow = null;
                    if ($searchForm !== '' && $candidates->count() > 1) {
                        $formLower = mb_strtolower($searchForm);
                        $matchedRow = $candidates->first(function ($row) use ($formLower) {
                            return str_contains(mb_strtolower((string)($row->study_form ?? '')), $formLower);
                        });
                    }
                    $matchedRow = $matchedRow ?? $candidates->first();

                    // Маппим колонки в зависимости от структуры таблицы матрицы
                    $matrixHours = [
                        'program_hours'  => (int)round((float)($matchedRow->hours ?? $matchedRow->total_hours ?? 0)),
                        'theory_hours'   => (int)round((float)($matchedRow->theory_hours ?? $matchedRow->theoretical_hours ?? $matchedRow->fulltime_theoretical_hours ?? 0)),
                        'prod_hours'     => (int)round((float)($matchedRow->practical_hours ?? $matchedRow->industrial_practice_hours ?? 0)),
                        'practice_hours' => (int)round((float)(in_array($targetTable, ['matrix_dpo', 'matrix_ot']) ? ($matchedRow->practical_hours ?? 0) : ($matchedRow->practice_hours ?? 0))),
                        'self_hours'     => (int)round((float)($matchedRow->self_study_hours ?? $matchedRow->distance_theoretical_hours ?? 0)),
                        'matched_source' => "{$targetTable} (ID: {$matchedRow->id}, Шифр: {$matchedRow->code})",
                    ];
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Ошибка подбора часов из матрицы для протокола #{$id}: " . $e->getMessage());
        }


        return view('protocols.report.create', compact(
            'protocol',
            'learningTypeName',
            'audiences',
            'teachers',
            'refCurators',
            'report',
            'matrixNum',
            'matrixHours',
            'allComissionMembers',
            'currentComission',
            'savedResults',
            'sapUsers'
        ));
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'id_protocol' => 'required',
            'order_num' => 'nullable|string|max:255',
            'audience_id' => 'nullable|string',
            'curator_id' => 'nullable|string',
        ]);

        $protocol = \App\Models\AppProtocol::with('demand.employees')->findOrFail($request->id_protocol);
        $employees = collect($protocol->demand?->employees ?? []);
        $rawResults = $request->input('results', []);
        $inputResults = [];

        foreach ($rawResults as $key => $values) {
            $targetId = null;
            if (is_numeric($key)) {
                $targetId = (int)$key;
            } elseif (is_string($key) && str_contains($key, '"id":')) {
                $decoded = json_decode($key, true);
                if (isset($decoded['id'])) {
                    $targetId = (int)$decoded['id'];
                }
            }

            if ($targetId && is_array($values)) {
                $inputResults[$targetId] = array_merge($inputResults[$targetId] ?? [], $values);
            }
        }

        $noShowCount = 0;
        $noPassedCount = 0;
        $programHours = (int)($request->input('program_hours') ?? $protocol->program_hours ?? 0);

        $cleanSnils = function ($value) {
            return !empty($value) ? preg_replace('/[^0-9]/', '', $value) : null;
        };

        foreach ($employees as $employee) {
            $empId = $employee->id;

            // $personnelNumber = $employee->tab_num ?? $employee->personnel_number ?? null;
            // $empSnilsRaw     = $employee->snils ?? null;
            // $empSnilsClean   = $cleanSnils($empSnilsRaw);

            $personnelNumber = $employee->tab_number ?? null;
            $empSnilsRaw     = $employee->pfr_certificate ?? null;
            $empSnilsClean   = $cleanSnils($empSnilsRaw);

            $fioFact = trim($employee->last_name ?? $employee->fio ?? $employee->name ?? '');
            if (!empty($employee->first_name)) {
                $fioFact = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
            }

            $birthDate = $employee->birth_date ?? null;
            $idStudents = null;

            // поиск по СНИЛС
            if (!empty($empSnilsClean)) {
                $oldStudent = DB::table('uc_ext_students')
                    ->whereNotNull('snils')
                    ->whereRaw("regexp_replace(snils, '[^0-9]', '', 'g') = ?", [$empSnilsClean])
                    ->first();

                if ($oldStudent) {
                    $idStudents = $oldStudent->id_student;
                }
            }

            // поиск по табельному
            if (!$idStudents && !empty($personnelNumber)) {
                $oldStudent = DB::table('uc_ext_students')
                    ->where('personnelnumber', (string)$personnelNumber)
                    ->first();

                if ($oldStudent) {
                    $idStudents = $oldStudent->id_student;
                }
            }

            if (!$idStudents && !empty($fioFact)) {
                $oldStudent = DB::table('uc_ext_students')->where('fullname', 'LIKE', "%{$fioFact}%")->first();
                if ($oldStudent) {
                    $idStudents = $oldStudent->id_student;
                }
            }

            if (!$idStudents) {
                $idStudents = (string) \Illuminate\Support\Str::uuid();

                DB::table('uc_ext_students')->insert([
                    'id_student'      => $idStudents,
                    'personnelnumber' => $personnelNumber,
                    'fullname'        => $fioFact,
                    'dateofbirth'     => $birthDate,
                    'snils'           => $empSnilsRaw,
                    'gender'          => $employee->gender ?? null,
                    'staff'           => $employee->position ?? 'Внешний слушатель',
                    'department'      => $employee->department ?? '—',
                    'namecompany'     => $employee->company ?? 'Сторонняя организация',
                ]);
            }

            $statusInput   = $inputResults[$empId]['status'] ?? 'зачет';
            $practiceMark  = !empty($inputResults[$empId]['practice_mark']) ? $inputResults[$empId]['practice_mark'] : null;
            $assignedRank  = !empty($inputResults[$empId]['assigned_rank']) ? $inputResults[$empId]['assigned_rank'] : null;
            $testResult    = !empty($inputResults[$empId]['test_result']) ? $inputResults[$empId]['test_result'] : null;
            $eisotNum      = !empty($inputResults[$empId]['eisot_num']) ? $inputResults[$empId]['eisot_num'] : null;
            $startFact     = !empty($inputResults[$empId]['start_fact']) ? $inputResults[$empId]['start_fact'] : $protocol->date_start;
            $endFact       = !empty($inputResults[$empId]['end_fact']) ? $inputResults[$empId]['end_fact'] : $protocol->date_end;
            $hoursFact     = isset($inputResults[$empId]['hours_fact']) ? (int)$inputResults[$empId]['hours_fact'] : $programHours;

            if ($hoursFact === 0 || $statusInput === 'неявка') {
                $noShowCount++;
                $noPassedCount++;
                $flagPassed = 'неявка';
            } elseif ($statusInput === 'неудовлетворительно' || $statusInput === 'незачет' || $hoursFact < $programHours) {
                $noPassedCount++;
                $flagPassed = 'не сдал';
            } else {
                $flagPassed = 'сдал';
            }

            $regNumber = is_numeric($eisotNum) ? (int)$eisotNum : null;
            $docNumber = !is_numeric($eisotNum) ? $eisotNum : null;

            $finalStaff = $currentData['assigned_staff'] ?? ($employee->position ?? '—');
            $existing = DB::table('uc_ext_studentsprotocol')
                ->where('id_protocol', $protocol->prot_id)
                ->where('id_students', $idStudents)
                ->first();

            if ($existing) {
                DB::table('uc_ext_studentsprotocol')
                    ->where('id_studentprotocol', $existing->id_studentprotocol)
                    ->update([
                        'markitog'        => $statusInput,
                        'status'          => $statusInput,
                        'markpractic'     => $practiceMark,
                        'assignedgrade'   => $assignedRank,
                        'resulttest'      => $testResult,
                        'regnumber'       => $regNumber,
                        'documentnumber'  => $docNumber,
                        'begindatefact'   => $startFact,
                        'enddatefact'     => $endFact,
                        'counthourfact'   => (float)$hoursFact,
                        'flagpassed'      => $flagPassed,
                        'fiofact'         => $fioFact,
                        'stafffact'       => $finalStaff,
                        'departfact'      => $employee->department ?? '—',
                        'namecompanyfact' => $employee->company ?? '—',
                        'gradefact'       => $employee->rank ?? null,
                    ]);
            } else {
                DB::table('uc_ext_studentsprotocol')->insert([
                    'id_studentprotocol' => (string) Str::uuid(),
                    'id_protocol'       => $protocol->prot_id,
                    'id_students'       => $idStudents,
                    'markitog'          => $statusInput,
                    'status'            => $statusInput,
                    'markpractic'       => $practiceMark,
                    'assignedgrade'     => $assignedRank,
                    'resulttest'        => $testResult,
                    'regnumber'         => $regNumber,
                    'documentnumber'    => $docNumber,
                    'begindatefact'     => $startFact,
                    'enddatefact'       => $endFact,
                    'counthourfact'     => (float)$hoursFact,
                    'flagpassed'        => $flagPassed,
                    'fiofact'           => $fioFact,
                    'stafffact'         => $finalStaff,
                    'departfact'        => $employee->department ?? '—',
                    'namecompanyfact'   => $employee->company ?? '—',
                    'gradefact'         => $employee->rank ?? null,
                ]);
            }
        }

        $totalWorkers = $employees->count();
        $showCount    = $totalWorkers - $noShowCount; // явки = всего - неявки
        $passedCount  = $totalWorkers - $noPassedCount; // сдали = всего - не сдало

        $reportData = [
            'id_protocol'     => $protocol->prot_id,
            'specialist'      => $request->input('user_oo_fio') ?? auth()->user()->name ?? 'Специалист ООО',
            'numbergroup'     => $protocol->prot_num,

            'countworker'     => $employees->where('position', 'Рабочий')->count(),
            'countleader'     => $employees->where('position', 'Руководитель')->count(),
            'countspecialist' => $employees->where('position', 'Специалист')->count(),

            'Show'            => $showCount,
            'noshow'          => $noShowCount,
            'passed'          => $passedCount,
            'nopassed'        => $noPassedCount,

            'fulltime'        => str_contains(mb_strtolower($protocol->demand?->education_form), 'очн') ? $employees->count() : 0,
            'distance'        => str_contains(mb_strtolower($protocol->demand?->education_form), 'дист') ? $employees->count() : 0,

            'countwoman'      => $employees->where('gender', 'Ж')->count(),
            'countman'        => $employees->where('gender', 'М')->count(),

            'countung'        => $employees->where('company_type', 'РН-ЮНГ')->count(),
            'countservise'    => $employees->where('company_type', 'Сервис')->count(),
            'countothercomp'  => $employees->where('company_type', 'Прочие')->count(),
            'countcash'       => $employees->where('company_type', 'Наличные')->count(),

            'teacher1'        => $protocol->demand?->teacher?->fio ?? '—',
            'specialistuc'    => $protocol->demand?->curator?->fio ?? '—',
            'curatorurp'      => $protocol->demand?->curator?->fio ?? '—',
            'audiencenumber'  => $request->input('audience_id') ?? '—',
        ];

        UcExtReport::updateOrCreate(
            ['id_protocol' => $protocol->prot_id],
            array_merge(['id_report' => (string) Str::uuid()], $reportData)
        );

        // пока что тестовое сохранение номера и даты приказа из отчета
        if ($request->filled('order_num')) {
            \App\Models\AppProtocol::where('prot_id', $protocol->prot_id)->update([
                'order_num'    => $request->input('order_num'),
                'order_date'   => $request->input('order_date') ?? $protocol->order_date,
                'date_edit'    => now(),
                'id_user_edit' => auth()->id() ?? 1,
                'group_num'    => $protocol->prot_num,
                'dem_id'       => $protocol->demand?->dem_id ?? $protocol->dem_id,
            ]);
        }

        // \App\Models\ProtocolComission::where('protocol_id', $protocol->prot_id)->delete();
        \App\Models\UcExtCommissionprotocol::where('id_protocol', $protocol->prot_id)->delete();

        // =========================================================================
        // АВТОМАТИЧЕСКИЙ ПЕРЕСЧЕТ ИТОГОВ ОБУЧЕНИЯ ДЛЯ ТАБЛИЦЫ uc_ext_report
        // =========================================================================
        try {
            $allSavedStudents = DB::table('uc_ext_studentsprotocol')
                ->where('id_protocol', $protocol->prot_id)
                ->get();

            $appearedCount = $allSavedStudents->filter(function ($row) {
                $mark = mb_strtolower(trim((string)($row->markitog ?? '')));
                return $mark !== '' && !str_contains($mark, 'неяв');
            })->count();

            $passedCount = $allSavedStudents->filter(function ($row) {
                $mark = mb_strtolower(trim((string)($row->markitog ?? '')));
                if ($mark === '' || str_contains($mark, 'неяв') || str_contains($mark, 'незач') || str_contains($mark, 'не зач') || str_contains($mark, 'неуд')) {
                    return false;
                }
                return in_array($mark, ['зачет', 'зачтено', 'удовл', 'удовлетворительно', 'хорошо', 'отлично', '3', '4', '5'])
                    || ((int)($row->flagpassed ?? 0) === 1);
            })->count();

            $noShowCount = $allSavedStudents->filter(function ($row) {
                $mark = mb_strtolower(trim((string)($row->markitog ?? '')));
                return str_contains($mark, 'неяв');
            })->count();

            $noPassedCount = max(0, $appearedCount - $passedCount);

            $employeesList = collect($protocol->demand?->employees ?? []);
            $menCount   = $employeesList->where('gender_key', '1')->count();
            $womenCount = $employeesList->where('gender_key', '2')->count();

            if (\Illuminate\Support\Facades\Schema::hasTable('uc_ext_report')) {
                $reportCols = \Illuminate\Support\Facades\Schema::getColumnListing('uc_ext_report');
                $statsUpdate = [];

                if (in_array('show', $reportCols))       { $statsUpdate['show']       = $appearedCount; }
                if (in_array('Show', $reportCols))       { $statsUpdate['Show']       = $appearedCount; }
                if (in_array('passed', $reportCols))     { $statsUpdate['passed']     = $passedCount; }
                if (in_array('noshow', $reportCols))     { $statsUpdate['noshow']     = $noShowCount; }
                if (in_array('nopassed', $reportCols))   { $statsUpdate['nopassed']   = $noPassedCount; }
                if (in_array('countman', $reportCols))   { $statsUpdate['countman']   = $menCount; }
                if (in_array('countwoman', $reportCols)) { $statsUpdate['countwoman'] = $womenCount; }

                if (!empty($statsUpdate)) {
                    DB::table('uc_ext_report')
                        ->where('id_protocol', $protocol->prot_id)
                        ->update($statsUpdate);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Ошибка пересчета итогов отчета для протокола #{$protocol->prot_id}: " . $e->getMessage());
        }


        // Председатель
        $chairmanFio = $request->input('chairman_fio');
        $dbChairman = DB::table('comission_members')->where('fio', $chairmanFio)->first();
        if (!empty($chairmanFio)) {
            \App\Models\UcExtCommissionprotocol::create([
                'id_compr'          => (string) Str::uuid(),
                'id_protocol'       => $protocol->prot_id,
                'id_listcommission' => $dbChairman ? $dbChairman->id : 1,
                'fio'               => $chairmanFio,
                'staff'             => $dbChairman ? $dbChairman->position : 'Председатель комиссии',
                'note'              => 'Председатель',
                'sortorder'         => 1,
            ]);
        }

        // Заместитель
        $deputyFio = $request->input('deputy_fio');
        if (!empty($deputyFio)) {
            $dbDeputy = DB::table('comission_members')->where('fio', $deputyFio)->first();
            \App\Models\UcExtCommissionprotocol::create([
                'id_compr'          => (string) Str::uuid(),
                'id_protocol'       => $protocol->prot_id,
                'id_listcommission' => $dbDeputy ? $dbDeputy->id : 2,
                'fio'               => $deputyFio,
                'staff'             => $dbDeputy ? $dbDeputy->position : 'Заместитель председателя',
                'note'              => 'Заместитель председателя',
                'sortorder'         => 2,
            ]);
        }

        // Члены комиссии
        $memberIds = $request->input('comission_members', []);
        foreach ($memberIds as $index => $mId) {
            $dbMember = DB::table('comission_members')->find($mId);
            if ($dbMember) {
                \App\Models\UcExtCommissionprotocol::create([
                    'id_compr'          => (string) Str::uuid(),
                    'id_protocol'       => $protocol->prot_id,
                    'id_listcommission' => $dbMember->id,
                    'fio'               => $dbMember->fio,
                    'staff'             => $dbMember->position,
                    'note'              => 'Член комиссии',
                    'sortorder'         => 3 + $index,
                ]);
            }
        }

        return redirect()->route('protocols.index')
            ->with('success', "Отчет по протоколу № {$protocol->prot_num} успешно сохранен.");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function searchOrders(Request $request)
    {
        $search = $request->query('q');

        if (empty($search)) {
            return response()->json([]);
        }

        $orders = DB::table('orders')
            ->where('order_number', 'LIKE', "%{$search}%")
            ->select('id', 'order_number', 'order_date')
            ->limit(5)
            ->get();

        return response()->json($orders);
    }

    public function getJson($id)
    {
        $protocol = AppProtocol::with([
            'demand.course',
            'demand.teacher',
            'demand.curator',
            'demand.profession',
            'demand.audience',
            'demand.provider',
            'demand.city',
            'editor'
        ])->find($id);

        if (!$protocol) {
            return response()->json(['error' => 'Протокол не найден'], 404);
        }

        return response()->json($protocol);
    }

    public function exportExcel(Request $request, \App\Services\ExcelReportService $excelService, $id = null)
    {
        $protocolId = $id ?? $request->input('protocol_id') ?? $request->query('protocol_id');

        if (!$protocolId) {
            return redirect()->back()->with('error', 'Не удалось определить идентификатор протокола для выгрузки Excel.');
        }

        $spreadsheet = $excelService->generateProtocolReport($protocolId);

        $protocol = \App\Models\AppProtocol::findOrFail($protocolId);
        $fileName = "protocol_report_{$protocol->prot_num}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            if (ob_get_contents()) {
                ob_end_clean();
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
            'Pragma' => 'public',
        ]);
    }

    public function exportYukos(\App\Services\ExcelReportService $excelService, $id)
    {
        try {
            $spreadsheet = $excelService->generateYukosProtocol($id);
            $protocol = AppProtocol::findOrFail($id);
            $fileName = "protocol_{$protocol->prot_num}.xlsx";

            return response()->streamDownload(function () use ($spreadsheet) {
                if (ob_get_contents()) {
                    ob_end_clean();
                }
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            }, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка экспорта ЮКОС: ' . $e->getMessage(), [
                'protocol_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Ошибка формирования протокола: ' . $e->getMessage());
        }
    }

    private function whereCyrillicLike($query, string $column, string $searchValue): void
    {
        $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
        $needle = mb_strtolower(trim($searchValue), 'UTF-8');

        $query->whereRaw(
            "TRANSLATE(CAST({$column} AS TEXT), '{$upper}', '{$lower}') LIKE ?",
            ["%{$needle}%"]
        );
    }
}
