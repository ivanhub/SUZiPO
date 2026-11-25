@php
    $item = $matrixOt ?? new \App\Models\MatrixOt();
@endphp

<div class="suzipo-grid-6">
    <!-- Ряд 1: № п/п, Шифр, Тип обучения, Вид обучения в СРО -->
    <div class="suzipo-col-1">
        <label for="row_number" class="suzipo-label">№ п/п</label>
        <input type="number" name="row_number" id="row_number" value="{{ old('row_number', $item->row_number) }}" class="suzipo-field">
        @error('row_number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="code" class="suzipo-label">Шифр</label>
        <input type="text" name="code" id="code" value="{{ old('code', $item->code) }}" class="suzipo-field">
        @error('code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="training_type" class="suzipo-label">Тип обучения</label>
        <input type="text" name="training_type" id="training_type" value="{{ old('training_type', $item->training_type) }}" class="suzipo-field">
        @error('training_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="sro_education_type" class="suzipo-label">Вид обучения в СРО</label>
        <input type="text" name="sro_education_type" id="sro_education_type" value="{{ old('sro_education_type', $item->sro_education_type) }}" class="suzipo-field">
        @error('sro_education_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 2: Реестр, Доп. код, Код ЮНГ, Код ЮЛ, Даты начала и окончания -->
    <div class="suzipo-col-1">
        <label for="registry_number" class="suzipo-label">№ по реестру</label>
        <input type="text" name="registry_number" id="registry_number" value="{{ old('registry_number', $item->registry_number) }}" class="suzipo-field">
        @error('registry_number') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="additional_code" class="suzipo-label">Доп. код</label>
        <input type="text" name="additional_code" id="additional_code" value="{{ old('additional_code', $item->additional_code) }}" class="suzipo-field">
        @error('additional_code') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="code_ucjung" class="suzipo-label">Код ЮНГ</label>
        <input type="text" name="code_ucjung" id="code_ucjung" value="{{ old('code_ucjung', $item->code_ucjung) }}" class="suzipo-field">
        @error('code_ucjung') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="code_ul" class="suzipo-label">Код ЮЛ</label>
        <input type="text" name="code_ul" id="code_ul" value="{{ old('code_ul', $item->code_ul) }}" class="suzipo-field">
        @error('code_ul') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
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

    <!-- Ряд 3: Наименование программы, Полное название, Форма обучения -->
    <div class="suzipo-col-2">
        <label for="program_name" class="suzipo-label">Наименование программы</label>
        <textarea name="program_name" id="program_name" rows="2" class="suzipo-field">{{ old('program_name', $item->program_name) }}</textarea>
        @error('program_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
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

    <!-- Ряд 4: Часы (6 колонок в один ряд) -->
    <div class="suzipo-col-1">
        <label for="total_hours" class="suzipo-label">Всего часов</label>
        <input type="number" step="0.01" name="total_hours" id="total_hours" value="{{ old('total_hours', $item->total_hours) }}" class="suzipo-field">
        @error('total_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="fulltime_theoretical_hours" class="suzipo-label">Теория (очно)</label>
        <input type="number" step="0.01" name="fulltime_theoretical_hours" id="fulltime_theoretical_hours" value="{{ old('fulltime_theoretical_hours', $item->fulltime_theoretical_hours) }}" class="suzipo-field">
        @error('fulltime_theoretical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="distance_theoretical_hours" class="suzipo-label">Теория с ДОТ</label>
        <input type="number" step="0.01" name="distance_theoretical_hours" id="distance_theoretical_hours" value="{{ old('distance_theoretical_hours', $item->distance_theoretical_hours) }}" class="suzipo-field">
        @error('distance_theoretical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="industrial_practice_hours" class="suzipo-label">Произв. практика</label>
        <input type="number" step="0.01" name="industrial_practice_hours" id="industrial_practice_hours" value="{{ old('industrial_practice_hours', $item->industrial_practice_hours) }}" class="suzipo-field">
        @error('industrial_practice_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="practical_hours" class="suzipo-label">Практика</label>
        <input type="number" step="0.01" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $item->practical_hours) }}" class="suzipo-field">
        @error('practical_hours') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
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

    <!-- Ряд 6: Категория слушателей, Группа, Форма контроля, Комиссия, Документ -->
    <div class="suzipo-col-2">
        <label for="student_category" class="suzipo-label">Категория слушателей</label>
        <input type="text" name="student_category" id="student_category" value="{{ old('student_category', $item->student_category) }}" class="suzipo-field">
        @error('student_category') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="group_capacity" class="suzipo-label">Группа (чел)</label>
        <input type="text" name="group_capacity" id="group_capacity" value="{{ old('group_capacity', $item->group_capacity) }}" class="suzipo-field">
        @error('group_capacity') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="control_form" class="suzipo-label">Форма контроля</label>
        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $item->control_form) }}" class="suzipo-field">
        @error('control_form') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="commission_type" class="suzipo-label">Тип комиссии</label>
        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $item->commission_type) }}" class="suzipo-field">
        @error('commission_type') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="issued_document" class="suzipo-label">Документ</label>
        <input type="text" name="issued_document" id="issued_document" value="{{ old('issued_document', $item->issued_document) }}" class="suzipo-field">
        @error('issued_document') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <!-- Ряд 7: Периодичность, Учи.Про, ИС, Преподаватель -->
    <div class="suzipo-col-2">
        <label for="periodicity" class="suzipo-label">Периодичность обучения</label>
        <input type="text" name="periodicity" id="periodicity" value="{{ old('periodicity', $item->periodicity) }}" class="suzipo-field">
        @error('periodicity') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="uchi_pro" class="suzipo-label">Учи.Про</label>
        <input type="text" name="uchi_pro" id="uchi_pro" value="{{ old('uchi_pro', $item->uchi_pro) }}" class="suzipo-field">
        @error('uchi_pro') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-1">
        <label for="information_system_entry" class="suzipo-label">Внесение в ИС</label>
        <input type="text" name="information_system_entry" id="information_system_entry" value="{{ old('information_system_entry', $item->information_system_entry) }}" class="suzipo-field">
        @error('information_system_entry') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
    </div>

    <div class="suzipo-col-2">
        <label for="teacher_name" class="suzipo-label">Преподаватель</label>
        <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $item->teacher_name) }}" class="suzipo-field">
        @error('teacher_name') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
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
        <label for="note" class="suzipo-label">Примечание</label>
        <textarea name="note" id="note" rows="2" class="suzipo-field">{{ old('note', $item->note) }}</textarea>
        @error('note') <p style="color: #EF4444; font-size: 11px; margin-top: 4px;">{{ $message }}</p> @enderror
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
