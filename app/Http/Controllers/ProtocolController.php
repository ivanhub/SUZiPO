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
            ];
        })->toArray();

        return view('protocols.index', compact('protocols', 'totalCount', 'counts', 'popupTeachers', 'popupLearningTypes', 'jsonProtocols'));
    }

    /**
     * Сохранение правок из Pop-up окна
     */
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

        $protocol->group_num       = $request->input('group_num');
        $protocol->order_num       = $request->input('order_num');
        $protocol->order_date      = $request->input('order_date');
        $protocol->prot_date       = $request->input('prot_date') ?? now();
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
        $refCurators = \Illuminate\Support\Facades\DB::table('ref_curators')->orderBy('fio')->get();
        $teachers = \App\Models\RequestsTeachers::orderBy('fio')->get();
        $report = \App\Models\UcExtReport::where('id_protocol', $id)->first();

        return view('protocols.report.create', compact('protocol', 'learningTypeName', 'audiences', 'teachers', 'refCurators', 'report', 'matrixNum'));
    }

    public function storeReport(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'id_protocol' => 'required',
            'order_num' => 'nullable|string|max:255',
            'audience_id' => 'nullable|string',
            'curator_id' => 'nullable|string',
        ]);

        $protocol = \App\Models\AppProtocol::with('demand.employees')->findOrFail($request->id_protocol);
        $employees = collect($protocol->demand?->employees ?? []);

        $inputResults = $request->input('results', []);

        $noShowCount = 0;
        $noPassedCount = 0;

        $programHours = (int)($request->input('program_hours') ?? $protocol->program_hours ?? 0);

        foreach ($employees as $employee) {
            $empId = $employee->id;
            
            // Берем данные строки сотрудника из формы, если их нет — ставим дефолты
            $status = $inputResults[$empId]['status'] ?? 'удовлетворительно';
            $hoursFact = isset($inputResults[$empId]['hours_fact']) ? (int)$inputResults[$empId]['hours_fact'] : $programHours;

            // 1. Логика ТЗ: Неявку считать, если фактическое кол-во часов равно 0 или выбран статус неявка
            if ($hoursFact === 0 || $status === 'неявка') {
                $noShowCount++;
                $noPassedCount++; // Неявившийся автоматически считается не сдавшим
                continue;
            }

            // 2. Логика ТЗ: Не сдали – при неудовлетворительной оценке или частичной неявке (фактические часы < часов по программе)
            if ($status === 'неудовлетворительно' || $hoursFact < $programHours) {
                $noPassedCount++;
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
            array_merge(['id_report' => (string) \Illuminate\Support\Str::uuid()], $reportData)
        );

        return redirect()->route('protocols.index')
            ->with('success', "Отчет по протоколу № {$protocol->prot_num} успешно сохранен. Показатели: Явок - {$showCount}, Неявок - {$noShowCount}, Сдали - {$passedCount}.");
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

     public function searchOrders(\Illuminate\Http\Request $request)
    {
        $search = $request->query('q');
        
        if (empty($search)) {
            return response()->json([]);
        }

        $orders = \Illuminate\Support\Facades\DB::table('orders')
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

    public function exportExcel(\Illuminate\Http\Request $request)
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
