<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <!-- Хлебные крошки и круглая кнопка Назад -->
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('requests.index') }}" class="suzipo-back-btn" title="Назад к списку заявок">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('requests.index') }}" style="color: var(--text-secondary); text-decoration: none;">Заявки на обучение</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Просмотр заявки #{{ $request->id }}</span>
            </div>
        </div>

        <!-- Заголовок и панель действий -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                <h1 class="suzipo-form-title" style="margin-bottom: 0;">Просмотр заявки #{{ $request->id }}</h1>

                @php
                    $st = $request->status;
                    if ($st === 'Создана' || $st === 'created') {
                        $badgeBg = 'rgba(245, 158, 11, 0.14)'; $badgeColor = '#D97706';
                    } elseif ($st === 'Отправлена' || $st === 'sent') {
                        $badgeBg = 'rgba(59, 130, 246, 0.14)'; $badgeColor = '#3B82F6';
                    } elseif ($st === 'in_progress') {
                        $badgeBg = 'rgba(139, 92, 246, 0.14)'; $badgeColor = '#8B5CF6';
                    } elseif ($st === 'urpedit') {
                        $badgeBg = 'rgba(249, 115, 22, 0.14)'; $badgeColor = '#EA580C';
                    } elseif ($st === 'accepted') {
                        $badgeBg = 'rgba(16, 185, 129, 0.14)'; $badgeColor = '#10B981';
                    } elseif ($st === 'rejected') {
                        $badgeBg = 'rgba(239, 68, 68, 0.14)'; $badgeColor = '#EF4444';
                    } else {
                        $badgeBg = 'var(--pill-btn-bg)'; $badgeColor = 'var(--text-secondary)';
                    }
                @endphp
                <span style="display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; background-color: {{ $badgeBg }}; color: {{ $badgeColor }};">
                    {{ $request->status_label }}
                </span>
            </div>

            <!-- Кнопки действий -->
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                <span class="suzipo-label">Дата создания:</span>
                <div>
                    <div class="suzipo-field" style="min-height: 38px;">{{ $request->issue_date ? $request->issue_date->format('d.m.Y') : '—' }}</div>
                </div>
                <a href="{{ route('request-employees.index', $request->id) }}"
                   style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; background-color: rgba(16, 185, 129, 0.14); color: #10B981; border: 1px solid #10B981; text-decoration: none;">
                    Сотрудники
                </a>

                @if(!(auth()->user()->hasAnyRole(['urp', 'urp admin']) && $request->status === 'in_progress'))
                <a href="{{ route('requests.edit', $request) }}" class="btn-suzipo-save" style="padding: 8px 16px; text-decoration: none;">
                    Редактировать
                </a>
                @endif
                <!-- Дата создания -->
                

                @if(auth()->user() && auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']) && $request->start_date && \Carbon\Carbon::parse($request->start_date)->diffInHours(now()) < 48 && $request->status !== 'Создана' && $request->status !== 'created' && $request->status !== 'urpedit')
                <form action="{{ route('requests.request-unlock', $request->id) }}" method="POST" style="display: inline-flex; margin: 0;">
                    @csrf
                    <button type="submit"
                            style="padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; background-color: rgba(249, 115, 22, 0.14); color: #EA580C; border: 1px solid #EA580C; cursor: pointer;"
                            onclick="return confirm('Отправить запрос на снятие защиты?')">
                        🔓 Запросить снять защиту
                    </button>
                </form>
                @endif

                @if(auth()->user()->hasAnyRole(['admin', 'ooo chief']))
                <a href="{{ route('requests.history', $request->id) }}"
                   style="padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; background-color: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border-color); text-decoration: none;">
                    🕐 История
                </a>
                @endif
            </div>
        </div>

        <!-- Основные реквизиты заявки -->
        <div class="suzipo-grid-6">
            <div class="suzipo-col-1">
                <span class="suzipo-label">Одноразовая</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->one_time ? 'Да' : 'Нет' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Дата начала</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->start_date ? $request->start_date->format('d.m.Y') : '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Дата окончания</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->end_date ? $request->end_date->format('d.m.Y') : '—' }}</div>
            </div>
                    <!-- Наименование ПО -->
                    <div>
                        <span class="suzipo-label">Наименование ПО</span>
                        <div class="suzipo-field" style="min-height: 38px;">{{ $request->course->course ?? '—' }}</div>
                    </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Дата оформления</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->issue_date ? $request->issue_date->format('d.m.Y') : '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Форма образования</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->education_form ?? '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Категория (ИТР/раб.)</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->employee_type ?? '—' }}</div>
            </div>

            <div class="suzipo-col-3">
                <span class="suzipo-label">Учебное заведение (провайдер)</span>
                <div class="suzipo-field" style="min-height: 40px;">{{ $request->provider->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-3">
                <span class="suzipo-label">Наименование курса (тематика)</span>
                <div class="suzipo-field" style="min-height: 40px;">{{ $request->course->course ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">С отрывом от производства</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->production_break ?? '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Страна</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->country ?? '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Город</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->city->city ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Присваиваемая профессия</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->profession->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Причина обучения</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->learnReason->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Ресурс обучения/оценки</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->learningResource->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Вид обучения/оценки</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->learningType->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Вид мероприятия</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->eventType->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Дисциплина</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->discipline->name ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">Себестоимость/Прибыль</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->cost_profit ?? '—' }}</div>
            </div>
        </div>
        <!-- Назначенные ресурсы -->
        <div class="suzipo-section-title">Назначенные ресурсы</div>
        <div class="suzipo-grid-6">
            <div class="suzipo-col-2">
                <span class="suzipo-label">Аудитория</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->audience->number ?? '—' }}</div>
            </div>

            <div class="suzipo-col-2">
                <span class="suzipo-label">ФИО преподавателя</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->teacher->fio ?? '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Куратор группы</span>
                <div class="suzipo-field" style="min-height: 38px;">{{ $request->curator->fio ?? '—' }}</div>
            </div>

            <div class="suzipo-col-1">
                <span class="suzipo-label">Резерв</span>
                <div class="suzipo-field" style="min-height: 38px;">
                    @if(isset($reserve) && $reserve !== null)
                        {{ $reserve }}
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>

        <!-- История изменений заявки -->
        <div class="suzipo-section-title">История изменений заявки</div>
        @php
            $fieldLabels = \App\Models\Request::getFieldLabels();
            $isOooGroup = auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief']);
        @endphp

        @if($request->activities->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($request->activities->reverse() as $activity)
                    @php
                        $changes = $activity->attribute_changes;
                        $isUrpEdit = $request->status === 'urpedit';
                        $highlightUrp = $isUrpEdit && $isOooGroup;
                    @endphp

                    <div style="background-color: {{ $highlightUrp ? 'rgba(239, 68, 68, 0.08)' : 'var(--bg-surface)' }}; border: 1px solid {{ $highlightUrp ? '#EF4444' : 'var(--border-color)' }}; border-radius: 14px; padding: 14px 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                            <div>
                                <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Внесённые изменения</div>
                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                                    {{ $activity->created_at->format('d.m.Y H:i') }} • {{ $activity->causer->name ?? 'Система' }}
                                </div>
                            </div>
                            @if($highlightUrp)
                                <span style="padding: 3px 10px; background-color: rgba(239, 68, 68, 0.14); color: #EF4444; border-radius: 9999px; font-size: 11px; font-weight: 600;">
                                    Изменено при доработке
                                </span>
                            @endif
                        </div>

                        @if($changes && isset($changes['old']))
                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 6px; font-size: 12px;">
                                @foreach($changes['old'] as $field => $oldValue)
                                    @php
                                        $newValue = $changes['attributes'][$field] ?? null;
                                        $fieldLabel = $fieldLabels[$field] ?? $field;
                                        $oldFormatted = \App\Models\Request::formatFieldValue($field, $oldValue);
                                        $newFormatted = \App\Models\Request::formatFieldValue($field, $newValue);
                                    @endphp
                                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; background-color: var(--bg-page); border-radius: 8px; padding: 7px 12px; border: 1px solid var(--border-color);">
                                        <span style="color: var(--text-secondary); font-weight: 500;">{{ $fieldLabel }}:</span>
                                        <span style="display: inline-flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span style="color: #EF4444; text-decoration: line-through;">{{ $oldFormatted }}</span>
                                            <span style="color: var(--text-secondary);">→</span>
                                            <span style="color: #10B981; font-weight: 500;">{{ $newFormatted }}</span>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">История изменений пуста</p>
        @endif
        <!-- История изменений сотрудников -->
        <div class="suzipo-section-title">История изменений сотрудников</div>
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
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($employeeActivities as $activity)
                    @php
                        $activityEmployee = $request->employees->firstWhere('id', $activity->subject_id);
                        $employeeName = $activityEmployee
                            ? $activityEmployee->full_name . ($activityEmployee->tab_number ? ' (таб. №' . $activityEmployee->tab_number . ')' : ' (ручной ввод)')
                            : 'Сотрудник #' . $activity->subject_id;
                    @endphp
                    <div style="background-color: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 14px; padding: 14px 16px;">
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                @if($activity->description === 'created')
                                    Добавлен сотрудник: {{ $employeeName }}
                                @elseif($activity->description === 'updated')
                                    Обновлены данные сотрудника: {{ $employeeName }}
                                @elseif($activity->description === 'deleted')
                                    Удален сотрудник: {{ $employeeName }}
                                @else
                                    {{ $activity->description }}: {{ $employeeName }}
                                @endif
                            </div>
                            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">
                                {{ $activity->created_at->format('d.m.Y H:i') }} • {{ $activity->causer->name ?? 'Система' }}
                            </div>
                        </div>

                        @if($activity->attribute_changes && isset($activity->attribute_changes['old']))
                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 6px; font-size: 12px;">
                                @foreach($activity->attribute_changes['old'] as $field => $oldValue)
                                    @php
                                        $newValue = $activity->attribute_changes['attributes'][$field] ?? null;
                                        $fieldLabel = $employeeFieldLabels[$field] ?? $field;
                                        $dateFields = ['absence_start_date', 'absence_end_date', 'distance_learning_date', 'fulltime_learning_date', 'document_issue_date', 'birth_date'];

                                        if (in_array($field, $dateFields)) {
                                            $oldValue = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('d.m.Y') : null;
                                            $newValue = $newValue ? \Carbon\Carbon::parse($newValue)->format('d.m.Y') : null;
                                        }
                                    @endphp
                                    @if($oldValue !== $newValue)
                                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; background-color: var(--bg-page); border-radius: 8px; padding: 7px 12px; border: 1px solid var(--border-color);">
                                            <span style="color: var(--text-secondary); font-weight: 500;">{{ $fieldLabel }}:</span>
                                            <span style="display: inline-flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span style="color: #EF4444; text-decoration: line-through;">{{ $oldValue ?: '—' }}</span>
                                                <span style="color: var(--text-secondary);">→</span>
                                                <span style="color: #10B981; font-weight: 500;">{{ $newValue ?: '—' }}</span>
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
            <p style="font-size: 13px; color: var(--text-secondary); margin: 0;">История изменений сотрудников пуста</p>
        @endif

        <!-- Нижняя панель кнопок -->
        <div class="suzipo-actions">
            <a href="{{ route('requests.index') }}" class="btn-suzipo-cancel">Назад к списку</a>

            <a href="{{ route('request-employees.index', $request->id) }}"
               style="padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; background-color: rgba(16, 185, 129, 0.14); color: #10B981; border: 1px solid #10B981; text-decoration: none;">
                Сотрудники
            </a>

            @if(!(auth()->user()->hasAnyRole(['urp', 'urp admin']) && $request->status === 'in_progress'))
                <a href="{{ route('requests.edit', $request) }}" class="btn-suzipo-save" style="text-decoration: none;">
                    Редактировать
                </a>
            @endif
        </div>
    </div>
</x-layouts.app-with-sidebar>
