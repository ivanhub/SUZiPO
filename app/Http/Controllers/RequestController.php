<?php

namespace App\Http\Controllers;

use App\Models\Request;
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function index(): View
    {
        $requests = Request::with(['user', 'provider', 'course', 'city'])->paginate(15);
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
	    'course_id' => 'nullable|exists:requests_courses,id',
            'new_course_name' => 'nullable|string|max:500',
	    'city_id' => 'nullable|exists:requests_cities,id',
            'new_city_name' => 'nullable|string|max:255',
            'provider_id' => 'nullable|exists:requests_providers,id',
            'new_provider_name' => 'nullable|string|max:500',
	    'profession_id' => 'nullable|exists:requests_professions,id',
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
//    $defaultCity = RequestsCity::where('city', 'LIKE', '%Нефтеюганск%')->first();
//    $defaultCityId = $defaultCity ? $defaultCity->id : null;

        if (empty($validated['city_id'])) {
            $defaultCity = RequestsCity::where('city', 'LIKE', '%Нефтеюганск%')->first();
            if ($defaultCity) {
                $validated['city_id'] = $defaultCity->id;
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';
        $validated['country'] = $validated['country'] ?? 'Россия';

        Request::create($validated);

        return redirect()->route('requests.index')
            ->with('success', 'Заявка создана успешно.');
    }

    public function show(Request $request): View
    {
        $request->load(['user', 'provider', 'course', 'city', 'profession', 'learnReason', 'learningResource', 'learningType', 'eventType', 'discipline', 'audience', 'teacher', 'curator']);
        return view('requests.show', compact('request'));
    }

    public function edit(Request $request): View
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

    public function update(HttpRequest $httpRequest, Request $request): RedirectResponse
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
            'course_id' => 'nullable|exists:requests_courses,id',
	    'new_course_name' => 'nullable|string|max:500',
	    'profession_id' => 'nullable|exists:requests_professions,id',
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


    $request->update($validated);
    
    return redirect()->route('requests.index')
        ->with('success', 'Заявка обновлена успешно.'); 
   }


    public function destroy(Request $request): RedirectResponse
    {
        $request->delete();
        return redirect()->route('requests.index')
            ->with('success', 'Заявка удалена.');
    }
}