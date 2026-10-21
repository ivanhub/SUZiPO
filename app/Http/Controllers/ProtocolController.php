<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UcExtReport;
use App\Models\AppProtocol;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;

class ProtocolController extends Controller
{
    public function index(Request $request)
    {
        // Инициализируем запрос с жадной загрузкой программ обучения
        $query = AppProtocol::with(['demand.course', 'demand.provider', 'editor']);

        if ($request->filled('activeTab') && $request->activeTab !== 'all') {
            // Если выбраны Черновики, Активные, Просроченные, Отмененные или Завершенные
            $query->where('prot_status', $request->activeTab);
        }

        // 1. Фильтр по ID протокола (первичный ключ)
        if ($request->filled('searchId')) {
            $query->where('prot_id', $request->searchId);
        }

        // 2. Фильтр по состоянию (статусу) протокола
        if ($request->filled('searchStatus')) {
            $query->where('prot_status', 'LIKE', '%' . $request->searchStatus . '%');
        }

        // 3. Фильтр по номеру протокола
        if ($request->filled('searchProtNum')) {
            $query->where('prot_num', 'LIKE', '%' . $request->searchProtNum . '%');
        }

        // 4. Фильтр по Редактору (связанная таблица пользователей/редакторов)
        if ($request->filled('searchEditor')) {
            $query->whereHas('editor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchEditor . '%');
            });
        }

        // 5. Фильтр по Наименованию курса (сквозной через app_demands -> bk_course)
        if ($request->filled('searchCourse')) {
            $query->whereHas('demand.course', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchCourse . '%');
            });
        }

        // 6. Фильтр по Номеру заявки (таблица app_demands)
        if ($request->filled('searchDemNum')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('dem_num', 'LIKE', '%' . $request->searchDemNum . '%');
            });
        }

        // 7. Фильтр по Квалификации (поле из таблицы app_demands)
        if ($request->filled('searchQual')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('qualification', 'LIKE', '%' . $request->searchQual . '%');
            });
        }

        // 8. Фильтр по ФИО Преподавателя (поле из таблицы app_demands)
        if ($request->filled('searchTeacher')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('teacher_name', 'LIKE', '%' . $request->searchTeacher . '%');
            });
        }

        // 9. Фильтр по Номеру аудитории (поле из таблицы app_demands)
        if ($request->filled('searchClassroom')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('classroom_number', 'LIKE', '%' . $request->searchClassroom . '%');
            });
        }

        // 10. Фильтр по Куратору группы (поле из таблицы app_demands)
        if ($request->filled('searchCurator')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('curator_name', 'LIKE', '%' . $request->searchCurator . '%');
            });
        }


        // Быстрая постраничная навигация без нагрузки на базу данных
        // $protocols = $query->orderBy('dateprotocol', 'desc')->simplePaginate(15);

        // $protocols = AppProtocol::with(['demand.course', 'editor'])
        //     ->orderBy('prot_id', 'desc')
        //     ->simplePaginate(15);

        // $protocols = $query->orderBy('prot_id', 'desc')->simplePaginate(15);
        $protocols = $query->orderBy('prot_id', 'desc')->paginate(15);

        $protocols->appends($request->all());

        // Расчет счетчиков для верхних табов-вкладок по полю flagapproved
        $totalCount = AppProtocol::count();

        $counts = AppProtocol::select('prot_status', DB::raw('count(*) as total'))
            ->whereNotNull('prot_status')
            ->groupBy('prot_status')
            ->pluck('total', 'prot_status')
            ->toArray();

        // AJAX-ответ для Alpine.js (возвращает только строки tbody)
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
        $learningTypeName =$protocol->demand?->educationType?->name ?? 'курсы';

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

            if ($studentInfo && !empty($studentInfo->snils)) { $key = preg_replace('/[^0-9]/', '', $studentInfo->snils); }
            if (empty($key) && $studentInfo && !empty($studentInfo->personnelnumber)) { $key = (string)$studentInfo->personnelnumber; }
            if (empty($key)) { 
                $matchedEmp = $protocol->demand?->employees->first(function($emp) use ($row) {
                    $fio = trim(($emp->last_name ?? '') . ' ' . ($emp->first_name ?? '') . ' ' . ($emp->middle_name ?? ''));
                    return trim($fio) === trim($row->fiofact);
                });
                
                $key = $matchedEmp ? (string)$matchedEmp->id : (string)$row->id_students; 
            }
            if (!empty($key)) { $savedResults->put($key, $row); }
        }

        $allComissionMembers = \App\Models\ComissionMember::orderBy('fio')->get();
        $currentComission = \App\Models\ProtocolComission::where('protocol_id', $id)->get();

        $allComissionMembers = \App\Models\ComissionMember::orderBy('fio')->get();
        $currentComission = \App\Models\ProtocolComission::where('protocol_id', $id)->get();

        $tabNumbers = $protocol->demand?->employees->pluck('tab_number')->filter()->toArray() ?? [];

        $sapUsers = DB::table('all_users_sap')
            ->whereIn('tab_number', $tabNumbers)
            ->get()
            ->keyBy('tab_number');

        return view('protocols.report.create', compact(
            'protocol', 'learningTypeName', 'audiences',
            'teachers', 'refCurators', 'report', 'matrixNum',
            'allComissionMembers', 'currentComission', 'savedResults', 'sapUsers'));
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

        $cleanSnils = function($value) {
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
                
                if ($oldStudent) { $idStudents = $oldStudent->id_student; }
            }

            // поиск по табельному
            if (!$idStudents && !empty($personnelNumber)) {
                $oldStudent = DB::table('uc_ext_students')
                    ->where('personnelnumber', (string)$personnelNumber)
                    ->first();
                
                if ($oldStudent) { $idStudents = $oldStudent->id_student; }
            }

            if (!$idStudents && !empty($fioFact)) {
                $oldStudent = DB::table('uc_ext_students')->where('fullname', 'LIKE', "%{$fioFact}%")->first();
                if ($oldStudent) { $idStudents = $oldStudent->id_student; }
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
            // Запись индивидуальных результатов в uc_ext_studentsprotocol
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
            
            'countworker'     => $employees->where('position', 'Рабочий')     ->count(),
            'countleader'     => $employees->where('position', 'Руководитель')->count(),
            'countspecialist' => $employees->where('position', 'Специалист')  ->count(),
            
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
                'order_num'  => $request->input('order_num'),
                'order_date' => $request->input('order_date') ?? $protocol->order_date,
                'date_edit'   => now(),
                'id_user_edit' => auth()->id() ?? 1
            ]);
        }

        // \App\Models\ProtocolComission::where('protocol_id', $protocol->prot_id)->delete();
        \App\Models\UcExtCommissionprotocol::where('id_protocol', $protocol->prot_id)->delete();

        // Председатель
        $chairmanFio = $request->input('comission.chairman_id');
        $dbChairman = DB::table('comission_members')->where('fio', $chairmanFio)->first();
        if ($dbChairman) {
            \App\Models\UcExtCommissionprotocol::create([
                'id_compr'          => (string) Str::uuid(),
                'id_protocol'       => $protocol->prot_id,
                'id_listcommission' => $dbChairman->id,
                'fio'               => $dbChairman->fio,
                'staff'             => $dbChairman->position,
                'note'              => 'Председатель',
                'sortorder'         => 1,
            ]);
        }

        // Заместитель
        $deputyFio = $request->input('comission.deputy_id');
        $dbDeputy = DB::table('comission_members')->where('fio', $deputyFio)->first();
        if ($dbDeputy) {
            \App\Models\UcExtCommissionprotocol::create([
                'id_compr'          => (string) Str::uuid(),
                'id_protocol'       => $protocol->prot_id,
                'id_listcommission' => $dbDeputy->id,
                'fio'               => $dbDeputy->fio,
                'staff'             => $dbDeputy->position,
                'note'              => 'Заместитель председателя',
                'sortorder'         => 2,
            ]);
        }

        // Члены комиссии
        $memberIds = $request->input('comission.members', []);
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

    public function exportExcel(Request $request)
    {
        $request->validate([
            'protocol_id' => 'required|exists:app_protocols,prot_id',
            'report_type' => 'required|string',
        ]);

        // Находим протокол со всеми базовыми связями
        $protocol = AppProtocol::with(['demand.course', 'demand.provider', 'editor'])->findOrFail($request->protocol_id);

        // 1. Инициализируем объект Excel-книги
        $spreadsheet = new Spreadsheet();

        // Лист 1: Отчет (Текущий активный лист по умолчанию)
        $sheetReport = $spreadsheet->getActiveSheet();
        $sheetReport->setTitle('Отчет');
        $sheetReport->setCellValue('A1', 'Отчет специалиста отдела организации обучения');
        $sheetReport->setCellValue('A3', 'Протокол №: ' . $protocol->prot_num);

        // Лист 2: Список
        $sheetList = $spreadsheet->createSheet();
        $sheetList->setTitle('Список');
        $sheetList->setCellValue('A1', 'Список обучаемых сотрудников');

        // Лист 3: Протокол
        $sheetProtocol = $spreadsheet->createSheet();
        $sheetProtocol->setTitle('Протокол');
        $sheetProtocol->setCellValue('A1', 'Официальный протокол заседания комиссии');

        // Сбрасываем указатель на первую вкладку, чтобы файл открывался красиво
        $spreadsheet->setActiveSheetIndex(0);

        // 2. Формируем заголовки для скачивания файла браузером
        $fileName = "Отчет_" . $protocol->prot_num . ".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . urlencode($fileName) . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
