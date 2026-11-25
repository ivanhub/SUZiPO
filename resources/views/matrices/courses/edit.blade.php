<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;">
        <div class="suzipo-breadcrumbs">
            <a href="{{ route('matrices.courses.index') }}" class="suzipo-back-btn" title="Назад к списку курсов">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div style="font-size: 12px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
                <a href="{{ route('dashboard') }}" style="color: var(--text-secondary); text-decoration: none;">Главная</a>
                <span>›</span>
                <a href="{{ route('matrices.courses.index') }}" style="color: var(--text-secondary); text-decoration: none;">Курсы</a>
                <span>›</span>
                <span style="color: var(--text-primary); font-weight: 500;">Редактирование курса #{{ $course->id }}</span>
            </div>
        </div>

        <h1 class="suzipo-form-title">Редактирование курса #{{ $course->id }}</h1>

        @if(session('error'))
            <div style="margin-bottom: 16px; padding: 12px 16px; background-color: rgba(239, 68, 68, 0.1); border: 1px solid #EF4444; color: #EF4444; border-radius: 10px; font-size: 13px;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('matrices.courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Раздел 1: Основные реквизиты, реестр и сроки действия (6-колоночная сетка) -->
            <div class="suzipo-section-title" style="margin-top: 0;">1. Идентификация программы и сроки действия</div>
            <div class="suzipo-grid-6">
                <div class="suzipo-col-2">
                    <label for="program" class="suzipo-label">Категория программы</label>
                    <input type="text" name="program" id="program" value="{{ old('program', $course->program) }}" class="suzipo-field">
                    @error('program') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="number" class="suzipo-label">№ п/п</label>
                    <input type="number" name="number" id="number" value="{{ old('number', $course->number) }}" class="suzipo-field">
                    @error('number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="code" class="suzipo-label">Шифр</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}" class="suzipo-field">
                    @error('code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="education_type" class="suzipo-label">Вид обучения</label>
                    <input type="text" name="education_type" id="education_type" value="{{ old('education_type', $course->education_type) }}" class="suzipo-field">
                    @error('education_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="sro_education_type" class="suzipo-label">Вид обучения в СРО</label>
                    <input type="text" name="sro_education_type" id="sro_education_type" value="{{ old('sro_education_type', $course->sro_education_type) }}" class="suzipo-field">
                    @error('sro_education_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="registry_number" class="suzipo-label">№ по реестру</label>
                    <input type="text" name="registry_number" id="registry_number" value="{{ old('registry_number', $course->registry_number) }}" class="suzipo-field">
                    @error('registry_number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="additional_code" class="suzipo-label">Доп. код</label>
                    <input type="text" name="additional_code" id="additional_code" value="{{ old('additional_code', $course->additional_code) }}" class="suzipo-field">
                    @error('additional_code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="date_start" class="suzipo-label">Дата начала</label>
                    <input type="date" name="date_start" id="date_start" value="{{ old('date_start', $course->date_start ? \Carbon\Carbon::parse($course->date_start)->format('Y-m-d') : '') }}" class="suzipo-field">
                    @error('date_start') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="date_end" class="suzipo-label">Дата окончания</label>
                    <input type="date" name="date_end" id="date_end" value="{{ old('date_end', $course->date_end ? \Carbon\Carbon::parse($course->date_end)->format('Y-m-d') : '') }}" class="suzipo-field">
                    @error('date_end') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="program_name" class="suzipo-label">Наименование программы</label>
                    <textarea name="program_name" id="program_name" rows="2" class="suzipo-field">{{ old('program_name', $course->program_name) }}</textarea>
                    @error('program_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="full_name" class="suzipo-label">Полное название</label>
                    <textarea name="full_name" id="full_name" rows="2" class="suzipo-field">{{ old('full_name', $course->full_name) }}</textarea>
                    @error('full_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="study_form" class="suzipo-label">Форма обучения</label>
                    <textarea name="study_form" id="study_form" rows="2" class="suzipo-field">{{ old('study_form', $course->study_form) }}</textarea>
                    @error('study_form') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
            </div>
                <!-- Раздел 2: Учебная нагрузка (часы) -->
                            <!-- Раздел 2: Учебная нагрузка (6 колонок в ряд) -->
            <div class="suzipo-section-title">2. Учебная нагрузка (часы)</div>
            <div class="suzipo-grid-6">
                <div class="suzipo-col-1">
                    <label for="hours" class="suzipo-label">Всего часов</label>
                    <input type="number" name="hours" id="hours" value="{{ old('hours', $course->hours) }}" class="suzipo-field">
                    @error('hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="theory_hours" class="suzipo-label">Теория (очно)</label>
                    <input type="number" name="theory_hours" id="theory_hours" value="{{ old('theory_hours', $course->theory_hours) }}" class="suzipo-field">
                    @error('theory_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="self_study_hours" class="suzipo-label">Теория с ДОТ</label>
                    <input type="number" name="self_study_hours" id="self_study_hours" value="{{ old('self_study_hours', $course->self_study_hours) }}" class="suzipo-field">
                    @error('self_study_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="practical_hours" class="suzipo-label">Произв. практика</label>
                    <input type="number" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $course->practical_hours) }}" class="suzipo-field">
                    @error('practical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="practice_hours" class="suzipo-label">Очная практика</label>
                    <input type="number" name="practice_hours" id="practice_hours" value="{{ old('practice_hours', $course->practice_hours) }}" class="suzipo-field">
                    @error('practice_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="distance_practical_hours" class="suzipo-label">Практика с ДОТ</label>
                    <input type="number" step="0.01" name="distance_practical_hours" id="distance_practical_hours" value="{{ old('distance_practical_hours', $course->distance_practical_hours) }}" class="suzipo-field">
                    @error('distance_practical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Раздел 3: Стоимость обучения без НДС (6 колонок в ряд) -->
            <div class="suzipo-section-title">3. Стоимость обучения без НДС (руб.)</div>
            <div class="suzipo-grid-6">
                <div class="suzipo-col-1">
                    <label for="cost_total" class="suzipo-label">Итого без НДС</label>
                    <input type="number" step="0.01" name="cost_total" id="cost_total" value="{{ old('cost_total', $course->cost_total) }}" class="suzipo-field">
                    @error('cost_total') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="cost_fulltime_theory" class="suzipo-label">Очная теория</label>
                    <input type="number" step="0.01" name="cost_fulltime_theory" id="cost_fulltime_theory" value="{{ old('cost_fulltime_theory', $course->cost_fulltime_theory) }}" class="suzipo-field">
                    @error('cost_fulltime_theory') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="cost_distance_theory" class="suzipo-label">Теория с ДОТ</label>
                    <input type="number" step="0.01" name="cost_distance_theory" id="cost_distance_theory" value="{{ old('cost_distance_theory', $course->cost_distance_theory) }}" class="suzipo-field">
                    @error('cost_distance_theory') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="cost_industrial_practice" class="suzipo-label">Произв. практика</label>
                    <input type="number" step="0.01" name="cost_industrial_practice" id="cost_industrial_practice" value="{{ old('cost_industrial_practice', $course->cost_industrial_practice) }}" class="suzipo-field">
                    @error('cost_industrial_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="cost_fulltime_practice" class="suzipo-label">Очная практика</label>
                    <input type="number" step="0.01" name="cost_fulltime_practice" id="cost_fulltime_practice" value="{{ old('cost_fulltime_practice', $course->cost_fulltime_practice) }}" class="suzipo-field">
                    @error('cost_fulltime_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="cost_distance_practice" class="suzipo-label">Практика с ДОТ</label>
                    <input type="number" step="0.01" name="cost_distance_practice" id="cost_distance_practice" value="{{ old('cost_distance_practice', $course->cost_distance_practice) }}" class="suzipo-field">
                    @error('cost_distance_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
            </div>
                            <!-- Раздел 4: Организационные параметры и заказчики (6-колоночная сетка) -->
            <div class="suzipo-section-title">4. Организационные параметры и заказчики</div>
            <div class="suzipo-grid-6">
                <div class="suzipo-col-2">
                    <label for="listener_category" class="suzipo-label">Категория слушателей</label>
                    <input type="text" name="listener_category" id="listener_category" value="{{ old('listener_category', $course->listener_category) }}" class="suzipo-field">
                    @error('listener_category') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="group_size" class="suzipo-label">Группа (чел)</label>
                    <input type="text" name="group_size" id="group_size" value="{{ old('group_size', $course->group_size) }}" class="suzipo-field">
                    @error('group_size') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="control_form" class="suzipo-label">Форма контроля</label>
                    <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $course->control_form) }}" class="suzipo-field">
                    @error('control_form') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="commission_type" class="suzipo-label">Тип комиссии</label>
                    <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $course->commission_type) }}" class="suzipo-field">
                    @error('commission_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="document_type" class="suzipo-label">Документ</label>
                    <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $course->document_type) }}" class="suzipo-field">
                    @error('document_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="periodicity" class="suzipo-label">Периодичность обучения</label>
                    <input type="text" name="periodicity" id="periodicity" value="{{ old('periodicity', $course->periodicity) }}" class="suzipo-field">
                    @error('periodicity') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="uchipro" class="suzipo-label">Учи.Про</label>
                    <input type="text" name="uchipro" id="uchipro" value="{{ old('uchipro', $course->uchipro) }}" class="suzipo-field">
                    @error('uchipro') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="info_system" class="suzipo-label">Инф. система</label>
                    <input type="text" name="info_system" id="info_system" value="{{ old('info_system', $course->info_system) }}" class="suzipo-field">
                    @error('info_system') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="teacher_fio" class="suzipo-label">Преподаватель</label>
                    <input type="text" name="teacher_fio" id="teacher_fio" value="{{ old('teacher_fio', $course->teacher_fio) }}" class="suzipo-field">
                    @error('teacher_fio') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="only_yung" class="suzipo-label">Только для ЮНГ</label>
                    <input type="text" name="only_yung" id="only_yung" value="{{ old('only_yung', $course->only_yung) }}" class="suzipo-field">
                    @error('only_yung') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="og" class="suzipo-label">ОГ</label>
                    <input type="text" name="og" id="og" value="{{ old('og', $course->og) }}" class="suzipo-field">
                    @error('og') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-2">
                    <label for="third_party" class="suzipo-label">Сторонние предприятия</label>
                    <input type="text" name="third_party" id="third_party" value="{{ old('third_party', $course->third_party) }}" class="suzipo-field">
                    @error('third_party') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="site_publication" class="suzipo-label">Публикация на сайте</label>
                    <input type="text" name="site_publication" id="site_publication" value="{{ old('site_publication', $course->site_publication) }}" class="suzipo-field">
                    @error('site_publication') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-1">
                    <label for="inf_mo" class="suzipo-label">ИНФ МО</label>
                    <input type="text" name="inf_mo" id="inf_mo" value="{{ old('inf_mo', $course->inf_mo) }}" class="suzipo-field">
                    @error('inf_mo') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-3">
                    <label for="notes" class="suzipo-label">Примечание</label>
                    <textarea name="notes" id="notes" rows="2" class="suzipo-field">{{ old('notes', $course->notes) }}</textarea>
                    @error('notes') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-3">
                    <label for="teacher_requirements" class="suzipo-label">Требования к преподавателям</label>
                    <textarea name="teacher_requirements" id="teacher_requirements" rows="2" class="suzipo-field">{{ old('teacher_requirements', $course->teacher_requirements) }}</textarea>
                    @error('teacher_requirements') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-3">
                    <label for="equipment" class="suzipo-label">Оборудование</label>
                    <textarea name="equipment" id="equipment" rows="2" class="suzipo-field">{{ old('equipment', $course->equipment) }}</textarea>
                    @error('equipment') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>

                <div class="suzipo-col-3">
                    <label for="equipment_location" class="suzipo-label">Местоположение оборудования</label>
                    <textarea name="equipment_location" id="equipment_location" rows="2" class="suzipo-field">{{ old('equipment_location', $course->equipment_location) }}</textarea>
                    @error('equipment_location') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="suzipo-actions">
                <a href="{{ route('matrices.courses.index') }}" class="btn-suzipo-cancel">Отмена</a>
                <button type="submit" class="btn-suzipo-save">Сохранить</button>
            </div>
        </form>
    </div>

    @if(session('error') || $errors->any())
        <script>
            console.error('Ошибка сохранения курса:', {
                sessionError: @json(session('error')),
                validationErrors: @json($errors->all())
            });
        </script>
    @endif
</x-layouts.app-with-sidebar>

