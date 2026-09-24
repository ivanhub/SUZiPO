<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Просмотр заявки #{{ $request->id }}</h2>
<div class="flex space-x-2">
    <a href="{{ route('requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Назад к списку</a>
    <a href="{{ route('request-employees.index', $request->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Сотрудники</a>
@if(!(auth()->user()->hasAnyRole(['urp', 'urp admin']) && $request->status === 'in_progress'))
<a href="{{ route('requests.edit', $request) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
@endif

<!-- Кнопка запроса снятия защиты (только для ooo, ooo admin, ooo chief) -->
@if(auth()->user() && auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) && $request->start_date && \Carbon\Carbon::parse($request->start_date)->diffInHours(now()) < 48 && $request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit')
<form action="{{ route('requests.request-unlock', $request->id) }}" method="POST" class="inline">
    @csrf
    <button type="submit" 
            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition"
            onclick="return confirm('Отправить запрос на снятие защиты?')">
        🔓 Запросить снять защиту
    </button>
</form>
@endif

</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
<!-- Статус -->
<div>
    <h3 class="text-sm font-medium text-gray-500">Статус</h3>
    <p class="mt-1 text-sm text-gray-900">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
            @if($request->status === 'Создана' || $request->status === 'created') bg-yellow-100 text-yellow-800
            @elseif($request->status === 'Отправлена' || $request->status === 'sent') bg-blue-100 text-blue-800
            @elseif($request->status === 'in_progress') bg-purple-100 text-purple-800
            @elseif($request->status === 'urpedit') bg-orange-100 text-orange-800
            @elseif($request->status === 'accepted') bg-green-100 text-green-800
            @elseif($request->status === 'rejected') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ $request->status_label }}
        </span>
    </p>
</div>

                    <!-- Одноразовая заявка -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Одноразовая заявка</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->one_time ? 'Да' : 'Нет' }}</p>
                    </div>

                    <!-- Дата начала обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата начала обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->start_date ? $request->start_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Дата окончания обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата окончания обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->end_date ? $request->end_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Дата оформления -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дата оформления</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->issue_date ? $request->issue_date->format('d.m.Y') : '—' }}</p>
                    </div>

                    <!-- Форма образования -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Форма образования</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->education_form ?? '—' }}</p>
                    </div>

                    <!-- ИТР/рабочие -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">ИТР/рабочие</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->employee_type ?? '—' }}</p>
                    </div>

                    <!-- С отрывом от производства -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">С отрывом от производства</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->production_break ?? '—' }}</p>
                    </div>

                    <!-- Учебное заведение (провайдер) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Учебное заведение (провайдер)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->provider->name ?? '—' }}</p>
                    </div>

                    <!-- Наименование курса -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Наименование курса (тематика)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->course->course ?? '—' }}</p>
                    </div>

                    <!-- Место проведения (страна) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Место проведения (страна)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->country ?? '—' }}</p>
                    </div>

                    <!-- Место проведения (город) -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Место проведения (город)</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->city->city ?? '—' }}</p>
                    </div>

                    <!-- Профессия -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Профессия, присваиваемая по результатам обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->profession->name ?? '—' }}</p>
                    </div>

                    <!-- Причина обучения -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Причина обучения</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learnReason->name ?? '—' }}</p>
                    </div>

                    <!-- Ресурс обучения/оценки -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Ресурс обучения/оценки</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learningResource->name ?? '—' }}</p>
                    </div>

                    <!-- Вид обучения/оценки -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Вид обучения/оценки</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->learningType->name ?? '—' }}</p>
                    </div>

                    <!-- Вид мероприятия -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Вид мероприятия</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->eventType->name ?? '—' }}</p>
                    </div>

                    <!-- Дисциплина -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Дисциплина</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->discipline->name ?? '—' }}</p>
                    </div>

                    <!-- Себестоимость/Прибыль -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Себестоимость/Прибыль</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->cost_profit ?? '—' }}</p>
                    </div>
                </div>

                <!-- Только для просмотра и редактирования -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-md font-semibold text-gray-900 mb-4">Назначенные ресурсы (только для просмотра и редактирования)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Аудитория</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->audience->number ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">ФИО преподавателя</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->teacher->fio ?? '—' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Куратор группы</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->curator->fio ?? '—' }}</p>
                        </div>
<div>
    <h3 class="text-sm font-medium text-gray-500">Резерв</h3>
    <p class="mt-1 text-sm text-gray-900">
        @if(isset($reserve) && $reserve !== null)
            {{ $reserve }}
        @else
            —
        @endif
    </p>
</div>
                </div>


<!-- История изменений -->
<div class="mt-8 pt-6 border-t border-gray-200">
    <h3 class="text-md font-semibold text-gray-900 mb-4">История изменений</h3>
    
    @php
        $fieldLabels = \App\Models\Request::getFieldLabels();
        $isOooGroup = auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief']);
    @endphp
    
    @if($request->activities->count() > 0)
        <div class="space-y-4">
            @foreach($request->activities->reverse() as $activity)
                @php
                    $changes = $activity->attribute_changes;
                    $isUrpEdit = $request->status === 'urpedit';
                @endphp
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 
                    {{ $isUrpEdit && $isOooGroup ? 'border-red-300 bg-red-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Внесенные изменения
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->created_at->format('d.m.Y H:i') }}
                                • {{ $activity->causer->name ?? 'Система' }}
                            </p>
                        </div>
                        @if($isUrpEdit && $isOooGroup)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Изменено при доработке</span>
                        @endif
                    </div>
                    
                    @if($changes && isset($changes['old']))
                        <div class="mt-3 text-xs space-y-1">
                            @foreach($changes['old'] as $field => $oldValue)
                                @php
                                    $newValue = $changes['attributes'][$field] ?? null;
                                    $fieldLabel = $fieldLabels[$field] ?? $field;
                                    $oldFormatted = \App\Models\Request::formatFieldValue($field, $oldValue);
                                    $newFormatted = \App\Models\Request::formatFieldValue($field, $newValue);
                                @endphp
                                <div class="flex justify-between items-center bg-white rounded px-2 py-1">
                                    <span class="text-gray-500 font-medium">{{ $fieldLabel }}:</span>
                                    <span class="ml-4 flex items-center space-x-2">
                                        <span class="text-red-600 line-through">{{ $oldFormatted }}</span>
                                        →
                                        <span class="text-green-600">{{ $newFormatted }}</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">История изменений пуста</p>
    @endif
</div>

<!-- История изменений сотрудников -->
<div class="mt-8 pt-6 border-t border-gray-200">
    <h3 class="text-md font-semibold text-gray-900 mb-4">История изменений сотрудников</h3>
    
    @php
        $employeeFieldLabels = [
            'tab_number' => 'Таб. номер',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'birth_date' => 'Дата рождения',
            'gender' => 'Пол',
            'gender_key' => 'Ключ пола',
            'pfr_certificate' => 'Свид. ПФР',
            'position' => 'Должность',
            'rank' => 'Разряд',
            'level_4_name' => 'Уровень 4',
            'level_3_name' => 'Уровень 3',
            'duv_b' => 'ДУвБ',
            'mvz' => 'МВЗ',
            'employee_category' => 'Категория',
            'absence_start_date' => 'Дата начала отсутствия',
            'absence_end_date' => 'Дата окончания отсутствия',
            'absence_reason' => 'Причина отсутствия',
            'absence_type' => 'Форма обучения',
            'distance_learning_date' => 'Дата заочного обучения',
            'fulltime_learning_date' => 'Дата очного обучения',
            'note' => 'Примечание',
            'document_issue_date' => 'Дата выдачи документа',
            'reissue_period' => 'Периодичность',
            'status' => 'Статус',
        ];
        
        $employeeActivities = \Spatie\Activitylog\Models\Activity::where('subject_type', \App\Models\RequestEmployee::class)
            ->whereIn('subject_id', $request->employees->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp
    
    @if($employeeActivities->count() > 0)
        <div class="space-y-4">
            @foreach($employeeActivities as $activity)
                @php
        // Находим сотрудника по subject_id
        $activityEmployee = $request->employees->firstWhere('id', $activity->subject_id);
        $employeeName = $activityEmployee 
            ? $activityEmployee->full_name . ($activityEmployee->tab_number ? ' (таб. №' . $activityEmployee->tab_number . ')' : ' (ручной ввод)')
            : 'Сотрудник #' . $activity->subject_id;
    @endphp
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                <p class="text-sm font-medium text-gray-900">
                    @if($activity->description === 'created')
                        Добавлен сотрудник: {{ $employeeName }}
                    @elseif($activity->description === 'updated')
                        Обновлены данные сотрудника: {{ $employeeName }}
                    @elseif($activity->description === 'deleted')
                        Удален сотрудник: {{ $employeeName }}
                    @else
                        {{ $activity->description }}: {{ $employeeName }}
                    @endif
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $activity->created_at->format('d.m.Y H:i') }}
                    • {{ $activity->causer->name ?? 'Система' }}
                </p>
            </div>
                    </div>
                    
@if($activity->attribute_changes && isset($activity->attribute_changes['old']))
    <div class="mt-3 text-xs space-y-1">
        @foreach($activity->attribute_changes['old'] as $field => $oldValue)
            @php
                $newValue = $activity->attribute_changes['attributes'][$field] ?? null;
                $fieldLabel = $employeeFieldLabels[$field] ?? $field;
                
                // Форматирование дат
                $dateFields = ['absence_start_date', 'absence_end_date', 'distance_learning_date', 'fulltime_learning_date', 'document_issue_date', 'birth_date'];
                
                if (in_array($field, $dateFields)) {
                    $oldValue = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d.m.Y') : null;
                    $newValue = $newValue ? \Carbon\Carbon::parse($newValue)->format('d.m.Y') : null;
                }
            @endphp
            @if($oldValue !== $newValue)
            <div class="flex justify-between items-center bg-white rounded px-2 py-1">
                <span class="text-gray-500 font-medium">{{ $fieldLabel }}:</span>
                <span class="ml-4 flex items-center space-x-2">
                    <span class="text-red-600 line-through">{{ $oldValue ?: '—' }}</span>
                    →
                    <span class="text-green-600">{{ $newValue ?: '—' }}</span>
                </span>
            </div>
            @endif
        @endforeach
    </div>
@endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-gray-500">История изменений сотрудников пуста</p>
    @endif
</div>

<div class="mt-6 flex justify-end space-x-2">
    <a href="{{ route('requests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Назад к списку</a>
    <a href="{{ route('request-employees.index', $request->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Сотрудники</a>
@if(!(auth()->user()->hasAnyRole(['urp', 'urp admin']) && $request->status === 'in_progress'))
<a href="{{ route('requests.edit', $request) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Редактировать</a>
@endif

<!-- Кнопка запроса снятия защиты (только для ooo, ooo admin, ooo chief) -->
@if(auth()->user() && auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) && $request->start_date && \Carbon\Carbon::parse($request->start_date)->diffInHours(now()) < 48 && $request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit')
<form action="{{ route('requests.request-unlock', $request->id) }}" method="POST" class="inline">
    @csrf
    <button type="submit" 
            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 transition"
            onclick="return confirm('Отправить запрос на снятие защиты?')">
        🔓 Запросить снять защиту
    </button>
</form>
@endif

</div>
            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>