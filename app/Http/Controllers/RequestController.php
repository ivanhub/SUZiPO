<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Models\RequestsProvider;
use App\Models\RequestsCourse;
use App\Models\RequestsCity;
use App\Models\RequestsProfession;
use App\Models\RequestsLearnReason;
use App\Models\RequestsLearningResource;
use App\Models\RequestsLearningType;
use App\Models\RequestsEventsType;
use App\Models\RequestsDiscipline;
use App\Models\RequestsAudience;
use App\Models\RequestsTeachers;
use App\Models\RequestsCurator;
use App\Models\Booking;
use App\Models\AppProtocol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class RequestController extends Controller
{

public function index(\Illuminate\Http\Request $httpRequest): View
{
    // Маппинг английских статусов (из URL) на русские (в БД)
    $statusMap = [
        'created' => 'Создана',
        'sent' => 'Отправлена',
        'in_progress' => 'in_progress', // если в БД уже используется английский
        'urpedit' => 'urpedit',         // если в БД уже используется английский
        'accepted' => 'accepted',
        'rejected' => 'rejected',
    ];
    
    $statusFilter = $httpRequest->get('status');
    
    // Маппим английский на русский
    $status = $statusMap[$statusFilter] ?? null;
    
    // Если статус не найден в мапе - используем как есть
    if (!$status && $statusFilter) {
        $status = $statusFilter;
    }
    
    $requests = RequestModel::with(['user', 'provider', 'course', 'city'])
        ->withCount('employees')
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->paginate(15);
    
    return view('requests.index', compact('requests'));
}


    public function create(): View
    {
        // Проверка права
        if (!auth()->user()->hasPermissionTo('create_requests')) {
            abort(403, 'У вас нет прав на создание заявок');
        }

        // $userDept = auth()->user()->department; // 'УРП' или 'УЦ'
        $userDept = 'УЦ'; // для теста
        $isUrp = (mb_strtoupper($userDept) === 'УРП');

        $nextGlobalNumbers = [
            'ЮНГ' => $this->getNextNumberForPrefix('ЮНГ'),
            'ЮЛ'  => $this->getNextNumberForPrefix('ЮЛ'),
            'ФЛ'  => $this->getNextNumberForPrefix('ФЛ'),
        ];

        $nextPrefixNumbers = [
            'ЮНГ' => \App\Models\Request::where('req_id', 'LIKE', '%-ЮНГ')->count() + 1,
            'ЮЛ'  => \App\Models\Request::where('req_id', 'LIKE', '%-ЮЛ')->count() + 1,
            'ФЛ'  => \App\Models\Request::where('req_id', 'LIKE', '%-ФЛ')->count() + 1,
        ];

        $providers = RequestsProvider::orderBy('name')->get();
        $courses = RequestsCourse::orderBy('course')->get();
        $cities = RequestsCity::orderBy('city')->get();
        $professions = RequestsProfession::orderBy('name')->get();
        $learnReasons = RequestsLearnReason::orderBy('name')->get();
        $learningResources = RequestsLearningResource::orderBy('name')->get();
        $learningTypes = RequestsLearningType::orderBy('name')->get();
        $eventsTypes = RequestsEventsType::orderBy('name')->get();
        $disciplines = RequestsDiscipline::orderBy('name')->get();
        $audiences = RequestsAudience::orderBy('number')->get();
        $teachers = RequestsTeachers::orderBy('fio')->get();
        $curators = RequestsCurator::orderBy('fio')->get();

        return view('requests.create', compact(
            'providers',
            'courses',
            'cities',
            'professions',
            'learnReasons',
            'learningResources',
            'learningTypes',
            'eventsTypes',
            'disciplines',
            'audiences',
            'teachers',
            'curators',
            'isUrp', 
            'nextGlobalNumbers',
            'nextPrefixNumbers'
        ));
    }

    private function getNextNumberForPrefix(string $prefix): int
    {
        if ($prefix === 'ЮНГ') {
            $maxNumber = RequestModel::where('req_id', 'LIKE', '%-ЮНГ')
                ->max('req_number');
        } else {
            $maxNumber = RequestModel::where(function($query) {
                    $query->where('req_id', 'LIKE', '%-ЮЛ')
                          ->orWhere('req_id', 'LIKE', '%-ФЛ');
                })
                ->max('req_number');
        }

        return $maxNumber ? (int)$maxNumber + 1 : 1;
    }

    public function store(HttpRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'one_time' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'issue_date' => 'nullable|date',
            'education_form' => 'nullable|string|max:100',
            'employee_type' => 'nullable|string|max:50',
            'production_break' => 'nullable|string|max:200',
            'provider_id' => 'nullable|exists:requests_providers,id',
            'course_id' => 'nullable|exists:requests_courses,id',
            'country' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:requests_cities,id',
            'profession_id' => 'nullable|exists:requests_professions,id',
            'learn_reason_id' => 'nullable|exists:requests_learn_reasons,id',
            'learning_resource_id' => 'nullable|exists:requests_learning_resources,id',
            'learning_type_id' => 'nullable|exists:requests_learning_types,id',
            'event_type_id' => 'nullable|exists:requests_events_types,id',
            'discipline_id' => 'nullable|exists:requests_disciplines,id',
            'cost_profit' => 'nullable|string|max:50',
            'new_course_name' => 'nullable|string|max:500',
            'new_city_name' => 'nullable|string|max:255',
            'new_provider_name' => 'nullable|string|max:500',
            'new_profession_name' => 'nullable|string|max:500',
            'req_prefix' => 'nullable|string|max:10',
	    'matrix_num' => 'nullable|integer|in:1,2,3,4,5',
        ]);

        // Создание нового курса
        if (!empty($validated['new_course_name'])) {
            $newCourse = RequestsCourse::firstOrCreate(
                ['course' => $validated['new_course_name']],
                ['course' => $validated['new_course_name']]
            );
            $validated['course_id'] = $newCourse->id;
        }
        unset($validated['new_course_name']);

        // Создание нового города
        if (!empty($validated['new_city_name'])) {
            $new = RequestsCity::firstOrCreate(
                ['city' => $validated['new_city_name']],
                ['city' => $validated['new_city_name']]
            );
            $validated['city_id'] = $new->id;
        }
        unset($validated['new_city_name']);

        // Создание нового провайдера
        if (!empty($validated['new_provider_name'])) {
            $new = RequestsProvider::firstOrCreate(
                ['name' => $validated['new_provider_name']],
                ['name' => $validated['new_provider_name']]
            );
            $validated['provider_id'] = $new->id;
        }
        unset($validated['new_provider_name']);

        // Создание новой профессии
        if (!empty($validated['new_profession_name'])) {
            $new = RequestsProfession::firstOrCreate(
                ['name' => $validated['new_profession_name']],
                ['name' => $validated['new_profession_name']]
            );
            $validated['profession_id'] = $new->id;
        }
        unset($validated['new_profession_name']);

        // Установка города по умолчанию "Нефтеюганск"
        if (empty($validated['city_id'])) {
            $defaultCity = RequestsCity::where('city', 'LIKE', '%Нефтеюганск%')->first();
            if ($defaultCity) {
                $validated['city_id'] = $defaultCity->id;
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'Создана';
        $validated['country'] = $validated['country'] ?? 'Россия';


        // $userDept = auth()->user()->department;
        $userDept = 'УЦ';
        $isUrp = (mb_strtoupper($userDept) === 'УРП');

        if ($isUrp) {
            $prefix = 'ЮНГ'; 
        } else {
            $prefix = in_array($validated['req_prefix'], ['ЮЛ', 'ФЛ']) ? $validated['req_prefix'] : 'ЮЛ';
        }

        $nextGlobalNumber = $this->getNextNumberForPrefix($prefix);

        $validated['req_number'] = $nextGlobalNumber;
        $prefixCount = RequestModel::where('req_id', 'LIKE', "%-{$prefix}")
            ->count();
        $nextPrefixNumber = $prefixCount + 1;
        $validated['req_id'] = "{$nextPrefixNumber}-{$prefix}";
        
        unset($validated['req_prefix']);
        $trainingRequest = RequestModel::create($validated);
        $action = $request->input('action');

        if ($action === 'save_and_employees') {
            return redirect()
                ->route('request-employees.index', $trainingRequest->id)
                ->with('success', 'Заявка создана. Добавьте сотрудников.');
        }

        // $lastRequest = RequestModel::where('req_id', 'LIKE', "%-ЮЛ")
        //     ->orderBy('id', 'desc')
        //     ->first();

        // $nextNumber = 1;

        // if ($lastRequest && $lastRequest->req_id) {
        //     $parts = explode('-', $lastRequest->req_id);
        //     $lastNumber = (int)$parts[0];
        //     $nextNumber = $lastNumber + 1;
        // }

        return redirect()->route('requests.index')
            ->with('success', "Заявка успешно создана под номером {$trainingRequest->req_id}.")
            ->with('request_id', $trainingRequest->id);
    }

    public function show(RequestModel $request): View
    {
        $request->load(['user', 'provider', 'course', 'city', 'profession',
                        'learnReason', 'learningResource', 'learningType', 
                        'eventType', 'discipline', 'audience', 'teacher', 'curator', 'activities']);
        $reserve = null;
        if ($request->audience_id) {
            $seats = $request->audience ? $request->audience->seats : null;

            if ($seats) {
                $employeesCount = \App\Models\RequestEmployee::where('request_id', $request->id)->count();
                $reserve = (int)$seats - (int)$employeesCount;
            }
        }

        return view('requests.show', compact('request', 'reserve'));
    }

    public function edit(RequestModel $request): View
    {
	// Проверка доступа
        $this->checkEditAccess($request);

        $providers = RequestsProvider::orderBy('name')->get();
        $courses = RequestsCourse::orderBy('course')->get();
        $cities = RequestsCity::orderBy('city')->get();
        $professions = RequestsProfession::orderBy('name')->get();
        $learnReasons = RequestsLearnReason::orderBy('name')->get();
        $learningResources = RequestsLearningResource::orderBy('name')->get();
        $learningTypes = RequestsLearningType::orderBy('name')->get();
        $eventsTypes = RequestsEventsType::orderBy('name')->get();
        $disciplines = RequestsDiscipline::orderBy('name')->get();
        $audiences = RequestsAudience::orderBy('number')->get();
        $teachers = RequestsTeachers::orderBy('fio')->get();
        $curators = RequestsCurator::orderBy('fio')->get();

        $reserve = null;
        if ($request->audience_id) {
            $seats = $request->audience ? $request->audience->seats : null;

            if ($seats) {
                $employeesCount = \App\Models\RequestEmployee::where('request_id', $request->id)->count();
                $reserve = (int)$seats - (int)$employeesCount;
            }
        }

	$matrixCourses = [];
	if (in_array($request->provider_id, [91, 92])) {
	    $courseService = app(\App\Services\CourseService::class);
	    $matrixCourses = $courseService->getCoursesByProvider($request->provider_id)->toArray();
	}
        return view('requests.edit', compact(
            'request',
            'providers',
            'courses',
            'matrixCourses',  
            'cities',
            'professions',
            'learnReasons',
            'learningResources',
            'learningTypes',
            'eventsTypes',
            'disciplines',
            'audiences',
            'teachers',
            'curators',
            'reserve'
        ));
    }

    public function update(HttpRequest $httpRequest, $id): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'one_time' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'issue_date' => 'nullable|date',
            'education_form' => 'nullable|string|max:100',
            'employee_type' => 'nullable|string|max:50',
            'production_break' => 'nullable|string|max:200',
            'provider_id' => 'nullable|exists:requests_providers,id',
            'course_id' => 'nullable|exists:requests_courses,id',
            'country' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:requests_cities,id',
            'profession_id' => 'nullable|exists:requests_professions,id',
            'learn_reason_id' => 'nullable|exists:requests_learn_reasons,id',
            'learning_resource_id' => 'nullable|exists:requests_learning_resources,id',
            'learning_type_id' => 'nullable|exists:requests_learning_types,id',
            'event_type_id' => 'nullable|exists:requests_events_types,id',
            'discipline_id' => 'nullable|exists:requests_disciplines,id',
            'cost_profit' => 'nullable|string|max:50',
            'audience_id' => 'nullable|exists:requests_audiences,id',
            'teacher_id' => 'nullable|exists:requests_teachers,id',
            'curator_id' => 'nullable|exists:requests_curators,id',
            'new_course_name' => 'nullable|string|max:500',
            'new_profession_name' => 'nullable|string|max:500',
	    'matrix_num' => 'nullable|integer|in:1,2,3,4,5',
        ]);

        if (!empty($validated['new_course_name'])) {
            $newCourse = RequestsCourse::firstOrCreate(
                ['course' => $validated['new_course_name']],
                ['course' => $validated['new_course_name']]
            );
            $validated['course_id'] = $newCourse->id;
        }
        unset($validated['new_course_name']);

        if (!empty($validated['new_profession_name'])) {
            $new = RequestsProfession::firstOrCreate(
                ['name' => $validated['new_profession_name']],
                ['name' => $validated['new_profession_name']]
            );
            $validated['profession_id'] = $new->id;
        }
        unset($validated['new_profession_name']);

        // Находим заявку по ID (НЕ из маршрута, а из параметра)
        $requestModel = RequestModel::findOrFail($id);


    // Проверка доступа
    $this->checkEditAccess($requestModel);
    
    // Проверяем, была ли заявка в статусе "Отправлена"
    $wasSent = $requestModel->status === 'Отправлена' || $requestModel->status === 'sent';
    
    // Сохраняем старые данные
    $oldData = $requestModel->toArray();

        // Сохраняем старого куратора
        $oldCuratorId = $requestModel->curator_id;

// Преобразование дат в формат Y-m-d
if (!empty($validated['start_date'])) {
    $validated['start_date'] = Carbon::parse($validated['start_date'])->format('Y-m-d');
} else {
    $validated['start_date'] = null;
}

if (!empty($validated['end_date'])) {
    $validated['end_date'] = Carbon::parse($validated['end_date'])->format('Y-m-d');
} else {
    $validated['end_date'] = null;
}

if (!empty($validated['issue_date'])) {
    $validated['issue_date'] = Carbon::parse($validated['issue_date'])->format('Y-m-d');
} else {
    $validated['issue_date'] = null;
}

        $requestModel->update($validated);

    // Если заявка была отправлена и её редактирует группа ooo/ooo admin/ooo chief
    if ($wasSent && auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief'])) {
        // Меняем статус на "В работе"
        $requestModel->update(['status' => 'in_progress']);
    }
    


	// Отправляем уведомление новому куратору
	  if ($requestModel->curator_id) {
	      $this->notifyCuratorAssigned($requestModel);
	  }
        // Создание или обновление бронирования
        if ($requestModel->audience_id && $requestModel->teacher_id && $requestModel->start_date) {
            $this->createOrUpdateBooking($requestModel);
        } else {
            $this->deleteBookingForRequest($requestModel);
        }

        // Обработка действия
        if ($httpRequest->input('action') === 'save_and_employees') {
            return redirect()
                ->route('request-employees.index', $requestModel->id)
                ->with('success', 'Заявка сохранена. Добавьте сотрудников.');
        }

        return redirect()->route('requests.index')
            ->with('success', 'Заявка обновлена успешно.');
    }

    public function destroy(RequestModel $request): RedirectResponse
    {
        // Удаляем бронирование перед удалением заявки
        $this->deleteBookingForRequest($request);

        $request->delete();
        return redirect()->route('requests.index')
            ->with('success', 'Заявка удалена.');
    }

    private function createOrUpdateBooking(RequestModel $requestModel): void
    {
        // Получаем даты начала и окончания из заявки
        $startDate = $requestModel->start_date;
        $endDate = $requestModel->end_date;

        // Если даты не заполнены, выходим
        if (!$startDate || !$endDate) {
            return;
        }

        // Проверяем, есть ли уже бронирование для этой заявки
        $booking = Booking::where('request_id', $requestModel->id)->first();

        // Проверяем конфликты на весь период
        $conflicts = $this->checkAvailabilityForPeriod(
            $requestModel->audience_id,
            $requestModel->teacher_id,
            $startDate,
            $endDate,
            $booking->id ?? null
        );

        if (!empty($conflicts)) {
            // Формируем сообщение об ошибке
            $messages = [];
            foreach ($conflicts as $date => $message) {
                $messages[] = Carbon::parse($date)->format('d.m.Y') . ' - ' . $message;
            }

            session()->flash('error', 'Невозможно забронировать: ' . implode('; ', $messages));
            return;
        }

        // Данные для бронирования
        $data = [
            'audience_id' => $requestModel->audience_id,
            'teacher_id' => $requestModel->teacher_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'notes' => 'Бронирование из заявки #' . $requestModel->id,
        ];

        if ($booking) {
            // Обновляем существующее бронирование
            $booking->update($data);
        } else {
            // Создаём новое бронирование
            $data['request_id'] = $requestModel->id;
            Booking::create($data);
        }
    }

    /**
     * Проверка доступности на период
     */
    private function checkAvailabilityForPeriod($audienceId, $teacherId, $startDate, $endDate, $excludeBookingId = null): array
    {
        $conflicts = [];

        // Проверяем каждую дату в периоде
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');

            // Проверяем аудиторию
            $audienceConflict = Booking::where('audience_id', $audienceId)
                ->whereDate('start_date', '<=', $dateStr)
                ->whereDate('end_date', '>=', $dateStr)
                ->where('id', '!=', $excludeBookingId)
                ->exists();

            if ($audienceConflict) {
                $conflicts[$dateStr] = 'Аудитория уже занята';
            }

            // Проверяем преподавателя
            $teacherConflict = Booking::where('teacher_id', $teacherId)
                ->whereDate('start_date', '<=', $dateStr)
                ->whereDate('end_date', '>=', $dateStr)
                ->where('id', '!=', $excludeBookingId)
                ->exists();

            if ($teacherConflict) {
                $conflicts[$dateStr] = 'Преподаватель уже занят';
            }

            $current->addDay();
        }

        return $conflicts;
    }

    /**
     * Удаление бронирования для заявки
     */
    private function deleteBookingForRequest(RequestModel $requestModel): void
    {
        Booking::where('request_id', $requestModel->id)->delete();
    }

    /*
     * Массовая или одиночная отправка заявок в ООО с созданием протоколов
     */
    public function sendToOoo(\Illuminate\Http\Request $httpRequest): \Illuminate\Http\RedirectResponse
    {
        // Извлекаем массив пришедших ID заявок из запроса
        $requestIds = $httpRequest->input('request_ids', []);

        if (empty($requestIds)) {
            return redirect()->back()->with('error', 'Не выбрано ни одной заявки для отправки.');
        }

    // Выбираем заявки со статусом "Создана" ИЛИ "urpedit"
    $requests = \App\Models\Request::whereIn('id', $requestIds)
        //->where('status', 'Создана')
	->whereIn('status', ['Создана', 'urpedit', 'created'])
        ->withCount('employees') // Добавляем подсчет сотрудников
        ->get();



  if ($requests->isEmpty()) {
    return redirect()->back()->with('error', 'Выбранные заявки уже отправлены или не могут быть обработаны.');
}

// ПРОВЕРКА: есть ли заявки без сотрудников
$requestsWithoutEmployees = $requests->filter(function ($request) {
    return $request->employees_count == 0;
});

if ($requestsWithoutEmployees->isNotEmpty()) {
    $numbers = $requestsWithoutEmployees->pluck('req_id')->implode(', ');

    return redirect()
        ->back()
        ->with('error', "Заявки без сотрудников не могут быть отправлены: {$numbers}. Добавьте сотрудников в эти заявки.");
}

// Выполняем операции атомарно в транзакции
\Illuminate\Support\Facades\DB::transaction(function () use ($requests) {
    foreach ($requests as $request) {

        // 1. Обновляем статус самой заявки
        $request->update([
            'status' => 'in_progress'
        ]);

        // 2. Создаем протокол в таблице app_protocols
        $request->protocols()->create([
            'prot_num'       => $request->req_id,
            'prot_status'    => 1,
            'prot_date'      => now(),
            'id_user_create' => auth()->id() ?? 1,
            'date_edit'      => now(),
            'id_user_edit'   => auth()->id() ?? 1,
            'date_start'     => $request->start_date ?? now(),
            'date_end'       => $request->end_date ?? now()->addDays(5),
            'row_version'    => 1,
        ]);
    }
});

        // Выполняем операции атомарно в транзакции
        \Illuminate\Support\Facades\DB::transaction(function () use ($requests) {
            foreach ($requests as $request) {

                // 1. Обновляем статус самой заявки
                $request->update([
                    'status' => 'Отправлена'
                ]);

                //Тестовый лог при отправке в ООО
                activity()
                    ->performedOn($request)
                    ->causedBy(auth()->user())
                    ->log('Заявка отправлена в ООО, создан протокол');

                // 2. Создаем протокол в таблице app_protocols
                $request->protocols()->create([
                    'prot_num'       => $request->req_id,
                    'prot_status'    => 1,
                    'prot_date'      => now(),
                    'id_user_create' => auth()->id() ?? 1,
                    'date_edit'      => now(),
                    'id_user_edit'   => auth()->id() ?? 1,
                    'date_start'     => $request->start_date ?? now(),
                    'date_end'       => $request->end_date ?? now()->addDays(5),
                    'row_version'    => 1,
                ]);
            }
        });

        // ОТПРАВКА УВЕДОМЛЕНИЯ НА mainooo@suzipo.ru
        $mainOoo = \App\Models\User::where('email', 'mainooo@suzipo.ru')->first();

        if ($mainOoo) {
            // Формируем список заявок для письма
            $requestList = '';
            foreach ($requests as $request) {
                $requestList .= "
                <tr>
                    <td style='padding: 8px; border: 1px solid #ddd;'>{$request->req_id}</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>" . ($request->course->course ?? '—') . "</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>" . ($request->start_date ? $request->start_date->format('d.m.Y') : '—') . "</td>
                    <td style='padding: 8px; border: 1px solid #ddd;'>" . ($request->end_date ? $request->end_date->format('d.m.Y') : '—') . "</td>
                </tr>
            ";
            }

            $subject = "Новые заявки на обучение (" . $requests->count() . " шт.)";

            $message = "
            <h2>Уведомление о новых заявках</h2>
            <p>Были отправлены новые заявки на обучение.</p>
            <table style='border-collapse: collapse; width: 100%;'>
                <thead>
                    <tr>
                        <th style='padding: 8px; border: 1px solid #ddd;'>Номер</th>
                        <th style='padding: 8px; border: 1px solid #ddd;'>Курс</th>
                        <th style='padding: 8px; border: 1px solid #ddd;'>Дата начала</th>
                        <th style='padding: 8px; border: 1px solid #ddd;'>Дата окончания</th>
                    </tr>
                </thead>
                <tbody>
                    {$requestList}
                </tbody>
            </table>
            <p>Пожалуйста, проверьте заявки в системе.</p>
        ";

            \Illuminate\Support\Facades\Mail::html($message, function ($mail) use ($mainOoo, $subject) {
                $mail->to($mainOoo->email)
                    ->subject($subject);
            });
        }

        $count = $requests->count();
        return redirect()->route('requests.index')
            ->with('success', "Успешно отправлено в ООО заявок: {$count}.");
    }


    /**
     * Проверка доступа к редактированию заявки
     */
    private function checkEditAccess(RequestModel $requestModel): ?\Illuminate\Http\RedirectResponse
    {
        $user = auth()->user();

/**
 * Проверка, заблокирована ли заявка (менее 48 часов до начала)
 */
private function isRequestLocked(RequestModel $requestModel): bool
{
    if (!$requestModel->start_date) {
        return false;
    }
    
    $startDate = Carbon::parse($requestModel->start_date);
    $now = Carbon::now();
    
    // Заблокирована если до начала менее 48 часов и статус не urpedit
    return $startDate->diffInHours($now) < 48 && $requestModel->status !== 'urpedit';
}

/**
 * Проверка доступа к редактированию заявки
 */
private function checkEditAccess(RequestModel $requestModel): ?\Illuminate\Http\RedirectResponse
{
    $user = auth()->user();
    
 $user = auth()->user();
    
    // Если пользователь не авторизован - редирект на логин
    if (!$user) {
        return redirect()->route('login');
    }
    
    // Админ имеет полный доступ
    if ($user->hasRole('admin')) {
        return null;
    }

// Если заявка в статусе "Создана" - только urp, urp admin, admin могут редактировать
if ($requestModel->status === 'Создана' || $requestModel->status === 'created') {
    if (!$user->hasAnyRole(['urp', 'urp admin', 'admin'])) {
        abort(403, 'У вас нет прав на редактирование заявки в статусе "Создана"');
    }
}
    
    // Если заявка отправлена - только ooo, ooo admin, ooo chief могут редактировать
    if ($requestModel->status === 'Отправлена' || $requestModel->status === 'sent') {
        if (!$user->hasAnyRole(['ooo', 'ooo admin', 'ooo chief'])) {
            abort(403, 'У вас нет прав на редактирование отправленной заявки');
        }
    }
    
    // Если статус urpedit - только urp, urp admin могут редактировать
    if ($requestModel->status === 'urpedit') {
        if (!$user->hasAnyRole(['urp', 'urp admin', 'admin'])) {
            abort(403, 'Заявка на доработке. Доступ разрешен только группам URP и URP Admin');
        }
    }
    
    // Если заявка заблокирована (48 часов) - только ooo, ooo admin, ooo chief могут видеть
    if ($this->isRequestLocked($requestModel)) {
        if (!$user->hasAnyRole(['ooo', 'ooo admin', 'ooo chief'])) {
            abort(403, 'Заявка заблокирована за 48 часов до начала обучения. Обратитесь к администратору.');
        }
    }
    
    return null;
}

/**
 * Запрос снятия защиты с заявки
 */
public function requestUnlock(int $id): RedirectResponse
{
    $user = auth()->user();
    $requestModel = RequestModel::findOrFail($id);
    
    \Illuminate\Support\Facades\Log::info('requestUnlock - user', [
        'email' => $user->email,
        'roles' => $user->roles->pluck('name')->implode(', '),
        'request_id' => $requestModel->id,
        'start_date' => $requestModel->start_date,
        'is_locked' => $this->isRequestLocked($requestModel),
    ]);

    
  // Только urp, urp admin могут запросить
    if (!$user->hasAnyRole(['urp', 'urp admin'])) {
        \Illuminate\Support\Facades\Log::error('requestUnlock - NO PERMISSION');
        abort(403, 'У вас нет прав на запрос снятия защиты');
    }
    
    // Заявка должна быть заблокирована
    if (!$this->isRequestLocked($requestModel)) {
        \Illuminate\Support\Facades\Log::error('requestUnlock - NOT LOCKED');
        return redirect()->back()->with('error', 'Заявка не заблокирована');
    }
    
    \Illuminate\Support\Facades\Log::info('requestUnlock - OK, sending emails');
    

    // Отмечаем запрос
    $requestModel->update(['protection_requested' => true]);
    
    // Отправляем email всем пользователям с ролью ooo admin
    $adminUsers = \App\Models\User::role('ooo admin')->get();

  if ($adminUsers->isEmpty()) {
        return redirect()->back()->with('error', 'Не незначен ответственный от ООО');
    }
    
    foreach ($adminUsers as $admin) {
        $unlockUrl = route('requests.unlock', $requestModel->id);
        
        $subject = "Запрос на снятие защиты с заявки #{$requestModel->req_id}";
        $message = "
            <h2>Запрос на снятие защиты</h2>
            <p>Пользователь <strong>{$user->name}</strong>, роль <strong>{$user->roles->pluck('name')->implode(', ')}</strong></p>
            <p>просит снять защиту с заявки <strong>#{$requestModel->req_id}</strong></p>
            <p><a href='{$unlockUrl}' target='_blank'  rel='noopener noreferrer'>Снять защиту</a></p>
        ";
        
        \Illuminate\Support\Facades\Mail::html($message, function ($mail) use ($admin, $subject) {
            $mail->to($admin->email)->subject($subject);
        });
    }
    
    return redirect()->back()->with('success', 'Запрос на снятие защиты отправлен');
}

/**
 * Снятие защиты с заявки (через ссылку из email)
 */
public function unlock(int $id): RedirectResponse
{
    $user = auth()->user();
    $requestModel = RequestModel::findOrFail($id);    
    // Только urp, urp admin могут снять защиту
    if (!$user->hasAnyRole(['urp', 'urp admin', 'admin'])) {
        abort(403, 'У вас нет прав на снятие защиты');
    }
    
    // Меняем статус на urpedit
    $requestModel->update([
        'status' => 'urpedit',
        'protection_requested' => false,
    ]);
    
    return redirect()->route('requests.edit', $requestModel->id)
        ->with('success', 'Защита снята. Заявка переведена в статус "На доработке".');
}

private function notifyCuratorAssigned(RequestModel $requestModel): void
{
    $curator = $requestModel->curator;
    
    if (!$curator || !$curator->email) {
        return;
    }
    
    $subject = "Вас назначили куратором заявки #{$requestModel->id}";
    
$message = "
    <h2>Назначение куратором</h2>
    <p><strong>Номер заявки:</strong> #{$requestModel->id}</p>
    <p><strong>Курс:</strong> " . ($requestModel->course->course ?? '—') . "</p>
    <p><strong>Дата начала:</strong> " . ($requestModel->start_date ? $requestModel->start_date->format('d.m.Y') : '—') . "</p>
    <p><strong>Дата окончания:</strong> " . ($requestModel->end_date ? $requestModel->end_date->format('d.m.Y') : '—') . "</p>
    <hr>
    <p>Вы были назначены куратором данной группы.</p>
";    
    \Illuminate\Support\Facades\Mail::html($message, function ($mail) use ($curator, $subject) {
        $mail->to($curator->email)->subject($subject);
    });
}

/**
 * Показать историю изменений заявки
 */
/**
 * Показать историю изменений заявки
 */
public function history(RequestModel $request, \Illuminate\Http\Request $httpRequest): View
{
    // Проверка доступа
    $user = auth()->user();
    if (!$user->hasAnyRole(['admin', 'ooo chief'])) {
        abort(403, 'У вас нет прав на просмотр истории изменений');
    }

    // Получаем все активности по заявке
    $requestActivities = \Spatie\Activitylog\Models\Activity::where('subject_type', RequestModel::class)
        ->where('subject_id', $request->id);
    
    // Получаем все активности по сотрудникам заявки
    $employeeIds = $request->employees->pluck('id');
    $employeeActivities = \Spatie\Activitylog\Models\Activity::where('subject_type', \App\Models\RequestEmployee::class)
        ->whereIn('subject_id', $employeeIds);
    
    // Объединяем активности без проверки дубликатов JSON (для PostgreSQL)
    $allActivities = $requestActivities->unionAll($employeeActivities)
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    // Загружаем causer
    $allActivities->load('causer');

    // Маппинг полей для сотрудников (так как в Request у вас только для заявок)
    $employeeLabels = [
        'tab_number' => 'Таб. номер',
        'last_name' => 'Фамилия',
        'first_name' => 'Имя',
        'middle_name' => 'Отчество',
        'position' => 'Должность',
        'absence_start_date' => 'Дата начала отсутствия',
        'absence_end_date' => 'Дата окончания отсутствия',
        'absence_reason' => 'Причина отсутствия',
        'absence_type' => 'Форма обучения',
        'note' => 'Примечание',
        'document_issue_date' => 'Дата выдачи документа',
        'reissue_period' => 'Периодичность',
    ];

    // Форматируем данные логов с помощью ваших бэкенд-функций перед отправкой в Blade
    $allActivities->getCollection()->transform(function ($activity) use ($employeeLabels) {
        $formattedChanges = [
            'labels' => [],
            'old' => [],
            'attributes' => []
        ];

        $changes = $activity->attribute_changes;
        $isEmployee = $activity->subject_type === \App\Models\RequestEmployee::class;
        $labelsSource = $isEmployee ? $employeeLabels : \App\Models\Request::getFieldLabels();

        // Запоминаем ФИО сотрудника, если это лог сотрудника
        if ($isEmployee) {
            $attrs = $changes['attributes'] ?? [];
            $activity->employee_fio = trim(($attrs['last_name'] ?? '') . ' ' . ($attrs['first_name'] ?? '') . ' ' . ($attrs['middle_name'] ?? ''));
        }

        // Обрабатываем старые и новые значения информационных полей
        if (isset($changes['attributes'])) {
            foreach ($changes['attributes'] as $field => $newValue) {
                if ($field === 'status' || $field === 'request_id') continue;

                $oldValue = $changes['old'][$field] ?? null;

                // Используем ваш метод форматирования значений из модели Request
                // (Для дат сотрудников добавим базовую обработку, если они приходят с таймзоной)
                if ($isEmployee && in_array($field, ['absence_start_date', 'absence_end_date', 'document_issue_date'])) {
                    $formattedOld = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d.m.Y') : '—';
                    $formattedNew = $newValue ? \Carbon\Carbon::parse($newValue)->format('d.m.Y') : '—';
                } else {
                    $formattedOld = \App\Models\Request::formatFieldValue($field, $oldValue);
                    $formattedNew = \App\Models\Request::formatFieldValue($field, $newValue);
                }

                $formattedChanges['labels'][$field] = $labelsSource[$field] ?? $field;
                $formattedChanges['old'][$field] = $formattedOld;
                $formattedChanges['attributes'][$field] = $formattedNew;
            }
        }

        $activity->formatted_changes = $formattedChanges;
        return $activity;
    });
    
    return view('requests.history', compact('request', 'allActivities'));
}


/**
 * Откат изменений
 */
public function rollback(RequestModel $request, \Spatie\Activitylog\Models\Activity $activity): RedirectResponse
{
    // Проверка доступа
    $user = auth()->user();
    if (!$user->hasAnyRole(['admin', 'ooo chief'])) {
        abort(403, 'У вас нет прав на откат изменений');
    }
    
    // Проверяем что активность относится к этой заявке
    if ($activity->subject_type === RequestModel::class && $activity->subject_id !== $request->id) {
        abort(404, 'Изменение не найдено');
    }
    
    if ($activity->subject_type === \App\Models\RequestEmployee::class) {
        $employee = \App\Models\RequestEmployee::find($activity->subject_id);
        if (!$employee || $employee->request_id !== $request->id) {
            abort(404, 'Изменение не найдено');
        }
    }
    
    // Выполняем откат
    $changes = $activity->attribute_changes;
    
    if (!$changes || !isset($changes['old'])) {
        return redirect()->back()->with('error', 'Невозможно откатить это изменение');
    }
    
    // ОТКАТ ДЛЯ ЗАЯВКИ
    if ($activity->subject_type === RequestModel::class) {
        // Сохраняем текущее состояние для лога отката
        $currentState = $request->toArray();
        
        // Применяем старые значения
        $request->update($changes['old']);
        
        // Логируем откат
        activity()
            ->performedOn($request)
            ->causedBy($user)
            ->withProperties([
                'attributes' => $changes['old'],
                'old' => $currentState,
                'rollback_of' => $activity->id,
            ])
            ->log('Откат изменений');
        
        return redirect()->back()->with('success', 'Изменения откачены успешно');
    }
    
    // ОТКАТ ДЛЯ СОТРУДНИКА
    if ($activity->subject_type === \App\Models\RequestEmployee::class) {
        $employee = \App\Models\RequestEmployee::find($activity->subject_id);
        
        if (!$employee) {
            // Сотрудник был удален - возвращаем его
            $oldData = $changes['old'];
            $oldData['request_id'] = $request->id;
            $employee = \App\Models\RequestEmployee::create($oldData);
            
            activity()
                ->performedOn($employee)
                ->causedBy($user)
                ->withProperties(['rollback_of' => $activity->id])
                ->log('Восстановлен сотрудник после отката');
        } else {
            // Сохраняем текущее состояние для лога отката
            $currentState = $employee->toArray();
            
            // Применяем старые значения
            $employee->update($changes['old']);
            
            // Логируем откат
            activity()
                ->performedOn($employee)
                ->causedBy($user)
                ->withProperties([
                    'attributes' => $changes['old'],
                    'old' => $currentState,
                    'rollback_of' => $activity->id,
                ])
                ->log('Откат изменений сотрудника');
        }
        
        return redirect()->back()->with('success', 'Изменения сотрудника откачены успешно');
    }
    
    return redirect()->back()->with('error', 'Не удалось откатить изменения');
}

}
