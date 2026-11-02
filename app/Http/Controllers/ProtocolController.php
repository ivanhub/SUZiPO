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
        $query = AppProtocol::with(['demand.course', 'demand.provider', 'editor']);

        if ($request->filled('activeTab') && $request->activeTab !== 'all') {
            $query->where('prot_status', $request->activeTab);
        }

        if ($request->filled('searchId')) {
            $query->where('prot_id', $request->searchId);
        }

        if ($request->filled('searchStatus')) {
            $query->where('prot_status', 'LIKE', '%' . $request->searchStatus . '%');
        }

        if ($request->filled('searchProtNum')) {
            $query->where('prot_num', 'LIKE', '%' . $request->searchProtNum . '%');
        }

        if ($request->filled('searchEditor')) {
            $query->whereHas('editor', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchEditor . '%');
            });
        }

        if ($request->filled('searchCourse')) {
            $query->whereHas('demand.course', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->searchCourse . '%');
            });
        }

        if ($request->filled('searchDemNum')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('dem_num', 'LIKE', '%' . $request->searchDemNum . '%');
            });
        }

        if ($request->filled('searchQual')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('qualification', 'LIKE', '%' . $request->searchQual . '%');
            });
        }

        if ($request->filled('searchTeacher')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('teacher_name', 'LIKE', '%' . $request->searchTeacher . '%');
            });
        }

        if ($request->filled('searchClassroom')) {
            $query->whereHas('demand', function ($q) use ($request) {
                $q->where('classroom_number', 'LIKE', '%' . $request->searchClassroom . '%');
            });
        }

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

        return view('protocols.report.create', compact(
            'protocol',
            'learningTypeName',
            'audiences',
            'teachers',
            'refCurators',
            'report',
            'matrixNum',
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


    // public function exportExcel(Request $request, $id = null)
    // {
    //     $protocolId = $id ?? $request->input('protocol_id') ?? $request->query('protocol_id');

    //     if (!$protocolId) {
    //         return redirect()->back()->with('error', 'Не удалось определить идентификатор протокола для выгрузки Excel.');
    //     }
    //     $protocol = \App\Models\AppProtocol::with(['demand.course', 'demand.learningType', 'demand.employees', 'demand.curator'])->findOrFail($id);
    //     $report = \App\Models\UcExtReport::where('id_protocol', $id)->first();
    //     $employees = collect($protocol->demand?->employees ?? []);
    //     $matrixNum = $protocol->demand?->matrix_num;

    //     $templatePath = storage_path('app/templates/protocol_blank.xlsx');
    //     if (!file_exists($templatePath)) {
    //         return redirect()->back()->with('error', 'Файл шаблона не найден по пути: storage/app/templates/protocol_blank.xlsx');
    //     }

    //     $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    //     $spreadsheet = $reader->load($templatePath);

    //     $rawSavedResults = DB::table('uc_ext_studentsprotocol')->where('id_protocol', $protocolId)->get();
    //     $savedResults = collect();
    //     foreach ($rawSavedResults as $row) {
    //         $studentInfo = DB::table('uc_ext_students')->where('id_student', $row->id_students)->first();
    //         $key = $studentInfo?->snils ? preg_replace('/[^0-9]/', '', $studentInfo->snils) : ($studentInfo?->personnelnumber ?? null);
    //         if (empty($key)) {
    //             $key = (string)$row->id_students;
    //         }
    //         if (!empty($key)) {
    //             $savedResults->put($key, $row);
    //         }
    //     }

    //     // Подгружаем состав комиссии
    //     $commissionRows = DB::table('uc_ext_commissionprotocol')->where('id_protocol', $protocolId)->orderBy('sortorder')->get();
    //     $chairmanFio = $commissionRows->where('note', 'Председатель')->first()?->fio ?? '—';
    //     $deputyFio   = $commissionRows->where('note', 'Заместитель председателя')->first()?->fio ?? '—';
    //     $membersList = $commissionRows->where('note', 'Член комиссии')->pluck('fio')->join(', ');

    //     $sheetReport = $spreadsheet->getSheetByName('Отчет');
    //     if ($sheetReport) {
    //         $sheetReport->setCellValue('C11', $report->specialist ?? '—');

    //         $sheetReport->setCellValue('C12', $protocol->order_num ?? '—');
    //         $sheetReport->setCellValue('E12', $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->format('d.m.Y') : '—');

    //         $sheetReport->setCellValue('C13', $protocol->prot_num ?? '—');
    //         $sheetReport->setCellValue('E13', $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->format('d.m.Y') : '—');

    //         $sheetReport->setCellValue('C14', $protocol->prot_num ?? '—');

    //         $sheetReport->setCellValue('C15', $protocol->demand?->audience_id ?? '—');

    //         $sheetReport->setCellValue('C17', $employees->where('position_type', 'Рабочий')->count());
    //         $sheetReport->setCellValue('C18', $employees->where('position_type', 'Руководитель')->count());
    //         $sheetReport->setCellValue('C19', $employees->where('position_type', 'Специалист')->count());

    //         $sheetReport->setCellValue('C20', $protocol->demand?->learningType?->name ?? 'Курсы');
    //         $sheetReport->setCellValue('C21', $protocol->demand?->education_form ?? 'Очное');

    //         $sheetReport->setCellValue('C22', 'Программа обучения: ' . ($protocol->demand?->course?->course ?? '—'));

    //         $sheetReport->setCellValue('C23', $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
    //         $sheetReport->setCellValue('C24', $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');
    //         $sheetReport->setCellValue('C26', $protocol->program_hours ?? 0);
    //         $sheetReport->setCellValue('C27', $protocol->theory_hours ?? 0);
    //         $sheetReport->setCellValue('C28', 0);
    //         $sheetReport->setCellValue('C29', $protocol->practice_hours ?? 0);

    //         $sheetReport->setCellValue('C31', 0);
    //         $sheetReport->setCellValue('C32', 0);

    //         $sheetReport->setCellValue('C33', $report ? $report->Show : $employees->count());
    //         $sheetReport->setCellValue('C34', $report ? $report->noshow : 0);
    //         $sheetReport->setCellValue('C35', $report ? $report->passed : $employees->count());
    //         $sheetReport->setCellValue('C36', $report ? $report->nopassed : 0);

    //         $sheetReport->setCellValue('C37', $report ? $report->countman : $employees->where('gender_key', '1')->count());
    //         $sheetReport->setCellValue('C38', $report ? $report->countwoman : $employees->where('gender_key', '2')->count());

    //         $sheetReport->setCellValue('C40', $employees->where('company_type', 'РН-ЮНГ')->count());
    //         $sheetReport->setCellValue('C41', $employees->where('company_type', 'Сервис')->count());
    //         $sheetReport->setCellValue('C42', $employees->where('company_type', 'Прочие')->count());
    //         $sheetReport->setCellValue('C43', $employees->where('company_type', 'Наличные')->count());

    //         $teacherFios = $protocol->demand?->teachers ? $protocol->demand->teachers->pluck('fio')->join(', ') : ($protocol->demand?->teacher?->fio ?? '—');
    //         $sheetReport->setCellValue('C44', $teacherFios);
    //         $sheetReport->setCellValue('C45', $report->specialist ?? '—');
    //         $sheetReport->setCellValue('C46', $protocol->demand?->curator?->fio ?? '—');
    //     }

    //     // Лист 2 (Список)
    //     $sheetList = $spreadsheet->getSheetByName('Список');
    //     if ($sheetList) {
    //         $sheetList->setCellValue('E6', ($protocol->order_num ?? '—'));
    //         $sheetList->setCellValue('C7', $protocol->demand?->course?->course ?? '—');
    //         $sheetList->setCellValue('C11', $protocol->prot_num ?? '—'); 
    //         $sheetList->setCellValue('D11', $report->specialist ?? '---'); 
    //         $sheetList->setCellValue('D12', $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
    //         $sheetList->setCellValue('D13', $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');

    //         $startRow = 17;
    //         foreach ($employees as $index => $employee) {
    //             $currentRow = $startRow + $index;

    //             if ($index >= 2) {
    //                 $sheetList->insertNewRowBefore($currentRow, 1);
    //             }

    //             $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
    //             if (empty($empFullName)) {
    //                 $empFullName = $employee->fio ?? $employee->name ?? '—';
    //             }

    //             $sheetList->setCellValue('B' . $currentRow, $index + 1); // № пп
    //             $sheetList->setCellValue('C' . $currentRow, $employee->tab_number ?? $employee->tab_num ?? '—'); // № табельный
    //             $sheetList->setCellValue('D' . $currentRow, $empFullName); // ФИО
    //             $sheetList->setCellValue('E' . $currentRow, $employee->position ?? '—'); // Должность
    //             $sheetList->setCellValue('F' . $currentRow, $employee->rank ?? '—'); // Разряд
    //             $sheetList->setCellValue('G' . $currentRow, $employee->company ?? $employee->level_3_name ?? '---'); // Предприятие
    //             $sheetList->setCellValue('H' . $currentRow, $employee->department ?? $employee->level_4_name ?? '—'); // Место работы
    //             $sheetList->setCellValue('I' . $currentRow, $employee->process_management ?? '—'); // Процессное управление
    //             $sheetList->setCellValue('J' . $currentRow, $employee->phone ?? '—'); // Контакты

    //             $birthDate = $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('d.m.Y') : '—';
    //             $sheetList->setCellValue('K' . $currentRow, $birthDate); // Дата рождения

    //             $sheetList->setCellValue('L' . $currentRow, $employee->snils ?? $employee->pfr_certificate ?? '—'); // СНИЛС
    //             $sheetList->setCellValue('M' . $currentRow, $employee->gender ?? '—'); // Пол
    //         }
    //     }

    //     // ЛИСТ 3: ПРОТОКОЛ
    //     $sheetTitle = 'Протокол Курсы';
    //     if ($matrixNum == 4) {
    //         $sheetTitle = 'Протокол ОТ';
    //     } elseif ($matrixNum == 5) {
    //         $sheetTitle = 'Протокол Мультимедиа';
    //     }

    //     $sheetProt = $spreadsheet->getSheetByName($sheetTitle) ?? $spreadsheet->getSheet(2);
    //     if ($sheetProt) {
    //         $sheetProt->setCellValue('E2', $protocol->prot_num ?? '—');
    //         $sheetProt->setCellValue('H2', $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->translatedFormat('d F Y') : '—');
    //         $sheetProt->setCellValue('F4', $protocol->order_num ?? '—');
    //         $sheetProt->setCellValue('I4', $protocol->order_date ? \Carbon\Carbon::parse($protocol->order_date)->translatedFormat('d F Y') : '—');
    //         $sheetProt->setCellValue('E5', $chairmanFio);
    //         $sheetProt->setCellValue('E6', $membersList ?: '-');
    //         $sheetProt->setCellValue('E8', $protocol->prot_num ?? '—');
    //         $sheetProt->setCellValue('G8', $protocol->demand?->course?->course ?? '—');
    //         $sheetProt->setCellValue('F9', $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
    //         $sheetProt->setCellValue('M9', $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');
    //         $sheetProt->setCellValue('F10', $protocol->program_hours ?? 8);

    //         $baseRowProt = 20; 
    //         $totalEmployees = $employees->count();

    //         foreach ($employees as $index => $employee) {
    //             $currentRow = $baseRowProt + $index;

    //             if ($index > 0) {
    //                 $sheetProt->insertNewRowBefore($currentRow, 1);
    //             }

    //             foreach (range('A', 'N') as $column) {
    //                 $sourceStyle = $sheetProt->getStyle($column . 18);
    //                 $sheetProt->duplicateStyle($sourceStyle, $column . $currentRow);

    //                 $sheetProt->getStyle($column . $currentRow)->getFont()->setBold(false);

    //                 if (!in_array($column, ['C', 'D'])) {
    //                     $sheetProt->getStyle($column . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //                 } else {
    //                     $sheetProt->getStyle($column . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    //                 }
    //                 $sheetProt->getStyle($column . $currentRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    //             }

    //             $sheetProt->getRowDimension($currentRow)->setRowHeight(-1);

    //             $empFullName = trim(($employee->last_name ?? '') . ' ' . ($employee->first_name ?? '') . ' ' . ($employee->middle_name ?? ''));
    //             if (empty($empFullName)) {
    //                 $empFullName = $employee->fio ?? $employee->name ?? '—';
    //             }

    //             $dbRow = $savedResults->get((string)$employee->id) ?? $savedResults->get(preg_replace('/[^0-9]/', '', $employee->snils ?? ''));
    //             $finalMark = $dbRow?->markitog ?? 'зачет';
    //             $eisotNum = $dbRow?->regnumber ?? $dbRow?->documentnumber ?? '—';

    //             $sheetProt->setCellValue('A' . $currentRow, $index + 1); // № пп
    //             $sheetProt->getCell('B' . $currentRow)->setValueExplicit($employee->tab_number ?? $employee->tab_num ?? '—', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // № табельный
    //             $sheetProt->setCellValue('C' . $currentRow, $empFullName); // ФИО
    //             $sheetProt->setCellValue('D' . $currentRow, $employee->position ?? '—'); // Должность/профессия
    //             $sheetProt->setCellValue('E' . $currentRow, $employee->rank ?? '—'); // Разряд
    //             $sheetProt->setCellValue('F' . $currentRow, $finalMark); // Итоговый зачет
    //             $sheetProt->setCellValue('G' . $currentRow, ''); // Оценка за практику

    //             // Рег. номер ЕИСОТ для Охраны Труда
    //             if ($matrixNum == 4) {
    //                 $sheetProt->getCell('H' . $currentRow)->setValueExplicit($eisotNum, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    //             } else {
    //                 $sheetProt->setCellValue('H' . $currentRow, '');
    //             }

    //             $sheetProt->setCellValue('I' . $currentRow, $employee->company ?? $employee->level_3_name ?? '-'); // Предприятие
    //             $sheetProt->setCellValue('J' . $currentRow, $finalMark); // Результат тестирования
    //             $sheetProt->setCellValue('K' . $currentRow, ''); // Подпись

    //             $dateStartFact = $dbRow?->begindatefact ? \Carbon\Carbon::parse($dbRow->begindatefact)->format('d.m.Y') : ($protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—');
    //             $dateEndFact = $dbRow?->enddatefact ? \Carbon\Carbon::parse($dbRow->enddatefact)->format('d.m.Y') : ($protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—');

    //             $sheetProt->setCellValue('L' . $currentRow, $dateStartFact); // Начало обучения (факт)
    //             $sheetProt->setCellValue('M' . $currentRow, $dateEndFact); // Окончание обучения (факт)
    //             $sheetProt->setCellValue('N' . $currentRow, (int)($dbRow?->counthourfact ?? $protocol->program_hours ?? 8)); // Фактически пройдено

    //             $sheetProt->getStyle('C' . $currentRow)->getAlignment()->setWrapText(true);
    //             $sheetProt->getStyle('I' . $currentRow)->getAlignment()->setWrapText(true);
    //         }

    //         $insertedRows = $totalEmployees > 2 ? $totalEmployees - 2 : 0;

    //         // TODO: Доделать состав комиссии
    //         $sheetProt->setCellValue('F' . (25 + $insertedRows), $chairmanFio);
    //         $sheetProt->setCellValue('F' . (29 + $insertedRows), 'Сидоров С.В.');
    //         $sheetProt->setCellValue('F' . (31 + $insertedRows), 'Логинова И.М.');
    //         $sheetProt->setCellValue('B' . (35 + $insertedRows), 'Исполнитель: ' . ($report->specialist ?? '—'));
    //     }

    //     $fileName = "protocol_report_{$protocol->prot_num}.xlsx";

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="' . urlencode($fileName) . '"');
    //     header('Cache-Control: max-age=0');

    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $writer->save('php://output');
    //     exit;
    // }
}
