<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\ReasonNonCertification;

class DirectoryController extends Controller
{
    public function reasonsNonCertification()
    {
        $reasons = ReasonNonCertification::orderBy('id')->get();
        return view('directories.reasons-non-certification.index', compact('reasons'));
    }

    public function storeReason(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ReasonNonCertification::create($validated);

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно добавлена.');
    }

    public function updateReason(\Illuminate\Http\Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $reason = ReasonNonCertification::findOrFail($id);
        $reason->update($validated);

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно обновлена.');
    }

    public function destroyReason($id)
    {
        $reason = ReasonNonCertification::findOrFail($id);
        $reason->delete();

        return redirect()->route('directories.reasons-non-certification')->with('success', 'Причина успешно удалена.');
    }

    public function courses() // +
    {
        return view('directories.courses.index');
    }

    public function cities() // +
    {
        return view('directories.cities.index');
    }

    public function professions() // +
    {
        return view('directories.professions.index');
    }

    public function qualifications() // -
    {
        return view('directories.qualifications.index');
    }

    public function employees() // -
    {
        return view('directories.employees.index');
    }

    public function departments() // -
    {
        return view('directories.departments.index');
    }

    public function trainingTypes() // +
    {
        return view('directories.training-types.index');
    }

    public function reasonsRejection() // -
    {
        return view('directories.reasons-rejection.index');
    }

    public function categories() // -
    {
        return view('directories.categories.index');
    }

    public function documentTypes() // +
    {
        $documentTypes = DocumentType::orderBy('id')->get();
        return view('directories.document-types.index', compact('documentTypes'));
    }

    public function storeDocumentType(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'sap_code' => 'nullable|string|max:50',
        ]);

        DocumentType::create($validated);

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно добавлен.');
    }

    public function updateDocumentType(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sap_code' => 'nullable|string|max:50',
        ]);

        $type = DocumentType::findOrFail($id);
        $type->update($validated);

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно обновлен.');
    }

    public function destroyDocumentType($id)
    {
        $type = DocumentType::findOrFail($id);
        $type->delete();

        return redirect()->route('directories.document-types')->with('success', 'Тип документа успешно удален.');
    }

    public function trainingType() // -
    {
        return view('directories.training-type.index');
    }

    public function courseAuthors() // -
    {
        return view('directories.course-authors.index');
    }

    public function trainingDirections() // -
    {
        return view('directories.training-directions.index');
    }

    public function costAllocation() // -
    {
        return view('directories.cost-allocation.index');
    }

    public function orders() // -
    {
        return view('directories.orders.index');
    }

    public function reasonsTraining() // +
    {
        return view('directories.reasons-training.index');
    }

    public function contracts() // -
    {
        return view('directories.contracts.index');
    }

    public function disciplines() // +
    {
        return view('directories.disciplines.index');
    }

    public function trainingAssessmentType() // +
    {
        return view('directories.training-assessment-type.index');
    }

    public function trainingAssessmentResources() // +
    {
        return view('directories.training-assessment-resources.index');
    }

    public function eventTypes() // +
    {
        return view('directories.event-types.index');
    }

    public function trainers() // +
    {
        return view('directories.trainers.index');
    }

    public function auditoriums() // +
    {
        return view('directories.auditoriums.index');
    }

    public function groupCurators() // +
    {
        return view('directories.group-curators.index');
    }

    public function absenceTypes() // -
    {
        return view('directories.absence-types.index');
    }

    public function index()
    {
        $safeCount = function (string $table): int {
            try {
                return \Illuminate\Support\Facades\Schema::hasTable($table)
                    ? \Illuminate\Support\Facades\DB::table($table)->count()
                    : 0;
            } catch (\Throwable $e) {
                return 0;
            }
        };

        $groups = [
            [
                'title' => 'Учебный процесс и программы',
                'items' => [
                    ['name' => 'Курсы', 'desc' => 'Каталог образовательных программ и курсов', 'route' => 'directories.courses.index', 'count' => $safeCount('requests_courses')],
                    ['name' => 'Учебные центры (Провайдеры)', 'desc' => 'Внешние и внутренние образовательные организации', 'route' => 'directories.providers.index', 'count' => $safeCount('requests_providers')],
                    ['name' => 'Дисциплины', 'desc' => 'Учебные дисциплины и модули подготовки', 'route' => 'directories.disciplines.index', 'count' => $safeCount('requests_disciplines')],
                    ['name' => 'Виды обучения', 'desc' => 'Формы и типы проведения обучения', 'route' => 'directories.learning-types.index', 'count' => $safeCount('requests_learning_types')],
                    ['name' => 'Направления обучения', 'desc' => 'Тематические направления подготовки', 'route' => 'directories.training-directions', 'count' => null],
                    ['name' => 'Виды подготовки', 'desc' => 'Классификатор видов подготовки', 'route' => 'directories.training-type', 'count' => null],
                    ['name' => 'Образовательные ресурсы', 'desc' => 'Платформы, ДОТ и учебные ресурсы', 'route' => 'directories.learning-resources.index', 'count' => $safeCount('requests_learning_resources')],
                    ['name' => 'Типы мероприятий', 'desc' => 'Виды учебных и аттестационных мероприятий', 'route' => 'directories.events-type.index', 'count' => $safeCount('requests_events_types')],
                    ['name' => 'Виды оценки обучения', 'desc' => 'Формы контроля и аттестации (зачёт, экзамен)', 'route' => 'directories.training-assessment-type', 'count' => null],
                ],
            ],
            [
                'title' => 'Персонал и организация',
                'items' => [
                    ['name' => 'Сотрудники (SAP)', 'desc' => 'Реестр работников предприятия из SAP', 'route' => 'all-users-sap.index', 'count' => $safeCount('all_users_sap')],
                    ['name' => 'Преподаватели', 'desc' => 'Штатные и привлекаемые преподаватели', 'route' => 'directories.teachers.index', 'count' => $safeCount('requests_teachers')],
                    ['name' => 'Кураторы', 'desc' => 'Ответственные кураторы обучения', 'route' => 'directories.curators.index', 'count' => $safeCount('requests_curators')],
                    ['name' => 'Авторы курсов', 'desc' => 'Разработчики методических материалов', 'route' => 'directories.course-authors', 'count' => null],
                    ['name' => 'Аудитории', 'desc' => 'Учебные классы и полигоны', 'route' => 'directories.audiences.index', 'count' => $safeCount('requests_audiences')],
                    ['name' => 'Подразделения', 'desc' => 'Структурные подразделения и цеха', 'route' => 'directories.departments', 'count' => null],
                    ['name' => 'Города', 'desc' => 'География проведения обучения', 'route' => 'directories.cities.index', 'count' => $safeCount('requests_cities')],
                ],
            ],
            [
                'title' => 'Профессии, квалификации и документы',
                'items' => [
                    ['name' => 'Профессии', 'desc' => 'Справочник рабочих профессий и должностей', 'route' => 'directories.professions.index', 'count' => $safeCount('requests_professions')],
                    ['name' => 'Квалификации', 'desc' => 'Разряды, группы допуска и квалификации', 'route' => 'directories.qualifications', 'count' => null],
                    ['name' => 'Категории персонала', 'desc' => 'Категории слушателей (РСС, рабочие)', 'route' => 'directories.categories', 'count' => null],
                    ['name' => 'Виды документов', 'desc' => 'Удостоверения, свидетельства, дипломы', 'route' => 'directories.document-types', 'count' => null],
                    ['name' => 'Договоры', 'desc' => 'Реестр договоров с учебными центрами', 'route' => 'directories.contracts', 'count' => null],
                    ['name' => 'Приказы / Распоряжения', 'desc' => 'Нормативные приказы по обучению', 'route' => 'directories.orders', 'count' => null],
                    ['name' => 'Распределение затрат', 'desc' => 'Статьи затрат и МВЗ', 'route' => 'directories.cost-allocation', 'count' => null],
                ],
            ],
            [
                'title' => 'Причины и статусы',
                'items' => [
                    ['name' => 'Причины обучения', 'desc' => 'Основания направления на обучение', 'route' => 'directories.learn-reasons.index', 'count' => $safeCount('requests_learn_reasons')],
                    ['name' => 'Причины неаттестации', 'desc' => 'Справочник оснований неаттестации', 'route' => 'directories.reasons-non-certification', 'count' => null],
                    ['name' => 'Причины отклонения', 'desc' => 'Причины возврата и отклонения заявок', 'route' => 'directories.reasons-rejection', 'count' => null],
                    ['name' => 'Виды отсутствия', 'desc' => 'Причины неявки слушателей', 'route' => 'directories.absence-types', 'count' => null],
                ],
            ],
        ];

        return view('directories.index', compact('groups'));
    }

}
