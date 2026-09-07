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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class RequestController extends Controller
{
    public function index(): View
    {
        $requests = RequestModel::with(['user', 'provider', 'course', 'city'])
            ->withCount('employees')
            ->paginate(15);
        
        return view('requests.index', compact('requests'));
    }

    public function create(): View
    {
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
            'providers', 'courses', 'cities', 'professions',
            'learnReasons', 'learningResources', 'learningTypes',
            'eventsTypes', 'disciplines', 'audiences', 'teachers', 'curators'
        ));
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

        $trainingRequest = RequestModel::create($validated);
        
        $action = $request->input('action');
        
        if ($action === 'save_and_employees') {
            return redirect()
                ->route('request-employees.index', $trainingRequest->id)
                ->with('success', 'Заявка создана. Добавьте сотрудников.');
        }
        
        return redirect()->route('requests.index')
            ->with('success', 'Заявка создана успешно.')
            ->with('request_id', $trainingRequest->id);
    }

    public function show(RequestModel $request): View
    {
        $request->load(['user', 'provider', 'course', 'city', 'profession', 'learnReason', 'learningResource', 'learningType', 'eventType', 'discipline', 'audience', 'teacher', 'curator']);
        return view('requests.show', compact('request'));
    }

    public function edit(RequestModel $request): View
    {
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

        return view('requests.edit', compact(
            'request', 'providers', 'courses', 'cities', 'professions',
            'learnReasons', 'learningResources', 'learningTypes',
            'eventsTypes', 'disciplines', 'audiences', 'teachers', 'curators'
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
        $requestModel->update($validated);

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

}