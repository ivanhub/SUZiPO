@php
    $item = $po ?? new \App\Models\MatrixPo();
@endphp

<div class="suzipo-grid-6">
    <!-- Ряд 1: № п/п, Шифр, Вид ПО, Вид обучения в СРО, № по реестру -->
    <div class="suzipo-col-1">
        <label for="number" class="suzipo-label">№ п/п</label>
        <input type="number" name="number" id="number" value="{{ old('number', $item->number) }}" class="suzipo-field">
        @error('number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="code" class="suzipo-label">Шифр</label>
        <input type="text" name="code" id="code" value="{{ old('code', $item->code) }}" class="suzipo-field">
        @error('code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="education_type" class="suzipo-label">Вид профессионального обучения</label>
        <input type="text" name="education_type" id="education_type" value="{{ old('education_type', $item->education_type) }}" class="suzipo-field">
        @error('education_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="sro_education_type" class="suzipo-label">Вид обучения в СРО</label>
        <input type="text" name="sro_education_type" id="sro_education_type" value="{{ old('sro_education_type', $item->sro_education_type) }}" class="suzipo-field">
        @error('sro_education_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 2: Реестр, Доп код, Даты, Разряд -->
    <div class="suzipo-col-1">
        <label for="registry_number" class="suzipo-label">№ по реестру</label>
        <input type="text" name="registry_number" id="registry_number" value="{{ old('registry_number', $item->registry_number) }}" class="suzipo-field">
        @error('registry_number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="additional_code" class="suzipo-label">Дополнительный код</label>
        <input type="text" name="additional_code" id="additional_code" value="{{ old('additional_code', $item->additional_code) }}" class="suzipo-field">
        @error('additional_code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="date_start" class="suzipo-label">Дата начала</label>
        <input type="date" name="date_start" id="date_start" value="{{ old('date_start', $item->date_start ? \Carbon\Carbon::parse($item->date_start)->format('Y-m-d') : '') }}" class="suzipo-field">
        @error('date_start') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="date_end" class="suzipo-label">Дата окончания</label>
        <input type="date" name="date_end" id="date_end" value="{{ old('date_end', $item->date_end ? \Carbon\Carbon::parse($item->date_end)->format('Y-m-d') : '') }}" class="suzipo-field">
        @error('date_end') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="rank" class="suzipo-label">Разряд</label>
        <input type="text" name="rank" id="rank" value="{{ old('rank', $item->rank) }}" class="suzipo-field">
        @error('rank') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 3: Наименование профессии, Полное название, Форма обучения -->
    <div class="suzipo-col-2">
        <label for="profession_name" class="suzipo-label">Наименование рабочей профессии</label>
        <textarea name="profession_name" id="profession_name" rows="2" class="suzipo-field">{{ old('profession_name', $item->profession_name) }}</textarea>
        @error('profession_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="full_name" class="suzipo-label">Полное название</label>
        <textarea name="full_name" id="full_name" rows="2" class="suzipo-field">{{ old('full_name', $item->full_name) }}</textarea>
        @error('full_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="study_form" class="suzipo-label">Форма обучения</label>
        <textarea name="study_form" id="study_form" rows="2" class="suzipo-field">{{ old('study_form', $item->study_form) }}</textarea>
        @error('study_form') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 4: Часы (6 колонок в один ряд, как в Figma) -->
    <div class="suzipo-col-1">
        <label for="hours" class="suzipo-label">Всего часов</label>
        <input type="number" name="hours" id="hours" value="{{ old('hours', $item->hours) }}" class="suzipo-field">
        @error('hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="theory_hours" class="suzipo-label">Теория</label>
        <input type="number" name="theory_hours" id="theory_hours" value="{{ old('theory_hours', $item->theory_hours) }}" class="suzipo-field">
        @error('theory_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="self_study_hours" class="suzipo-label">Самостоятельно</label>
        <input type="number" name="self_study_hours" id="self_study_hours" value="{{ old('self_study_hours', $item->self_study_hours) }}" class="suzipo-field">
        @error('self_study_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="practical_hours" class="suzipo-label">Произв. практика</label>
        <input type="number" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $item->practical_hours) }}" class="suzipo-field">
        @error('practical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="practice_hours" class="suzipo-label">Практика</label>
        <input type="number" name="practice_hours" id="practice_hours" value="{{ old('practice_hours', $item->practice_hours) }}" class="suzipo-field">
        @error('practice_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="distance_practical_hours" class="suzipo-label">Практика с ДОТ</label>
        <input type="number" step="0.01" name="distance_practical_hours" id="distance_practical_hours" value="{{ old('distance_practical_hours', $item->distance_practical_hours) }}" class="suzipo-field">
        @error('distance_practical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>
    <!-- Ряд 5: Стоимость обучения без НДС (6 колонок в один ряд) -->
    <div class="suzipo-col-1">
        <label for="cost_total" class="suzipo-label">Итого без НДС</label>
        <input type="number" step="0.01" name="cost_total" id="cost_total" value="{{ old('cost_total', $item->cost_total) }}" class="suzipo-field">
        @error('cost_total') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="cost_fulltime_theory" class="suzipo-label">Очная теория</label>
        <input type="number" step="0.01" name="cost_fulltime_theory" id="cost_fulltime_theory" value="{{ old('cost_fulltime_theory', $item->cost_fulltime_theory) }}" class="suzipo-field">
        @error('cost_fulltime_theory') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="cost_distance_theory" class="suzipo-label">Теория с ДОТ</label>
        <input type="number" step="0.01" name="cost_distance_theory" id="cost_distance_theory" value="{{ old('cost_distance_theory', $item->cost_distance_theory) }}" class="suzipo-field">
        @error('cost_distance_theory') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="cost_industrial_practice" class="suzipo-label">Произв. практика</label>
        <input type="number" step="0.01" name="cost_industrial_practice" id="cost_industrial_practice" value="{{ old('cost_industrial_practice', $item->cost_industrial_practice) }}" class="suzipo-field">
        @error('cost_industrial_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="cost_fulltime_practice" class="suzipo-label">Очная практика</label>
        <input type="number" step="0.01" name="cost_fulltime_practice" id="cost_fulltime_practice" value="{{ old('cost_fulltime_practice', $item->cost_fulltime_practice) }}" class="suzipo-field">
        @error('cost_fulltime_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="cost_distance_practice" class="suzipo-label">Практика с ДОТ</label>
        <input type="number" step="0.01" name="cost_distance_practice" id="cost_distance_practice" value="{{ old('cost_distance_practice', $item->cost_distance_practice) }}" class="suzipo-field">
        @error('cost_distance_practice') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 6: Категория слушателей, Группа, Форма аттестации, Комиссия, Документ -->
    <div class="suzipo-col-2">
        <label for="listener_category" class="suzipo-label">Категория слушателей</label>
        <input type="text" name="listener_category" id="listener_category" value="{{ old('listener_category', $item->listener_category) }}" class="suzipo-field">
        @error('listener_category') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="group_size" class="suzipo-label">Группа (чел)</label>
        <input type="text" name="group_size" id="group_size" value="{{ old('group_size', $item->group_size) }}" class="suzipo-field">
        @error('group_size') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="control_form" class="suzipo-label">Форма аттестации</label>
        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $item->control_form) }}" class="suzipo-field">
        @error('control_form') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="commission_type" class="suzipo-label">Тип комиссии</label>
        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $item->commission_type) }}" class="suzipo-field">
        @error('commission_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="document_type" class="suzipo-label">Документ</label>
        <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $item->document_type) }}" class="suzipo-field">
        @error('document_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 7: Периодичность, Учи.Про, ИС, Преподаватель -->
    <div class="suzipo-col-2">
        <label for="periodicity" class="suzipo-label">Периодичность обучения</label>
        <input type="text" name="periodicity" id="periodicity" value="{{ old('periodicity', $item->periodicity) }}" class="suzipo-field">
        @error('periodicity') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="uchipro" class="suzipo-label">Учи.Про</label>
        <input type="text" name="uchipro" id="uchipro" value="{{ old('uchipro', $item->uchipro) }}" class="suzipo-field">
        @error('uchipro') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="info_system" class="suzipo-label">Внесение в ИС</label>
        <input type="text" name="info_system" id="info_system" value="{{ old('info_system', $item->info_system) }}" class="suzipo-field">
        @error('info_system') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="teacher_fio" class="suzipo-label">Преподаватель</label>
        <input type="text" name="teacher_fio" id="teacher_fio" value="{{ old('teacher_fio', $item->teacher_fio) }}" class="suzipo-field">
        @error('teacher_fio') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 8: Заказчики и публикация -->
    <div class="suzipo-col-1">
        <label for="only_yung" class="suzipo-label">Только для ЮНГ</label>
        <input type="text" name="only_yung" id="only_yung" value="{{ old('only_yung', $item->only_yung) }}" class="suzipo-field">
        @error('only_yung') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="og" class="suzipo-label">ОГ</label>
        <input type="text" name="og" id="og" value="{{ old('og', $item->og) }}" class="suzipo-field">
        @error('og') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="third_party" class="suzipo-label">Сторонние предприятия</label>
        <input type="text" name="third_party" id="third_party" value="{{ old('third_party', $item->third_party) }}" class="suzipo-field">
        @error('third_party') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="site_publication" class="suzipo-label">Публикация на сайте</label>
        <input type="text" name="site_publication" id="site_publication" value="{{ old('site_publication', $item->site_publication) }}" class="suzipo-field">
        @error('site_publication') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="inf_mo" class="suzipo-label">ИНФ МО</label>
        <input type="text" name="inf_mo" id="inf_mo" value="{{ old('inf_mo', $item->inf_mo) }}" class="suzipo-field">
        @error('inf_mo') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 9-10: Примечания, требования и оборудование -->
    <div class="suzipo-col-3">
        <label for="notes" class="suzipo-label">Примечание</label>
        <textarea name="notes" id="notes" rows="2" class="suzipo-field">{{ old('notes', $item->notes) }}</textarea>
        @error('notes') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-3">
        <label for="teacher_requirements" class="suzipo-label">Требования к преподавателям</label>
        <textarea name="teacher_requirements" id="teacher_requirements" rows="2" class="suzipo-field">{{ old('teacher_requirements', $item->teacher_requirements) }}</textarea>
        @error('teacher_requirements') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-3">
        <label for="equipment" class="suzipo-label">Оборудование</label>
        <textarea name="equipment" id="equipment" rows="2" class="suzipo-field">{{ old('equipment', $item->equipment) }}</textarea>
        @error('equipment') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-3">
        <label for="equipment_location" class="suzipo-label">Местоположение оборудования</label>
        <textarea name="equipment_location" id="equipment_location" rows="2" class="suzipo-field">{{ old('equipment_location', $item->equipment_location) }}</textarea>
        @error('equipment_location') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>
</div>
