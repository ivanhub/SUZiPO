@php
    $item = $po ?? new \App\Models\MatrixPo();
@endphp

<!-- Раздел 1: Идентификация программы ПО и сроки действия -->
<h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">1. Идентификация программы ПО и сроки действия</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div>
        <label for="number" class="block text-sm font-medium text-gray-700 mb-1">№ п/п</label>
        <input type="number" name="number" id="number" value="{{ old('number', $item->number) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Шифр</label>
        <input type="text" name="code" id="code" value="{{ old('code', $item->code) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="education_type" class="block text-sm font-medium text-gray-700 mb-1">Вид профессионального обучения</label>
        <input type="text" name="education_type" id="education_type" value="{{ old('education_type', $item->education_type) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('education_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sro_education_type" class="block text-sm font-medium text-gray-700 mb-1">Вид обучения в СРО</label>
        <input type="text" name="sro_education_type" id="sro_education_type" value="{{ old('sro_education_type', $item->sro_education_type) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('sro_education_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="registry_number" class="block text-sm font-medium text-gray-700 mb-1">№ Программы по реестру</label>
        <input type="text" name="registry_number" id="registry_number" value="{{ old('registry_number', $item->registry_number) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('registry_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="additional_code" class="block text-sm font-medium text-gray-700 mb-1">Дополнительный код</label>
        <input type="text" name="additional_code" id="additional_code" value="{{ old('additional_code', $item->additional_code) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('additional_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Дата введения в действие</label>
        <input type="date" name="date_start" id="date_start" value="{{ old('date_start', $item->date_start ? \Carbon\Carbon::parse($item->date_start)->format('Y-m-d') : '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('date_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Дата окончания действия</label>
        <input type="date" name="date_end" id="date_end" value="{{ old('date_end', $item->date_end ? \Carbon\Carbon::parse($item->date_end)->format('Y-m-d') : '') }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('date_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="rank" class="block text-sm font-medium text-gray-700 mb-1">Разряд</label>
        <input type="text" name="rank" id="rank" value="{{ old('rank', $item->rank) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('rank') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="profession_name" class="block text-sm font-medium text-gray-700 mb-1">Наименование рабочей профессии</label>
        <input type="text" name="profession_name" id="profession_name" value="{{ old('profession_name', $item->profession_name) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('profession_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Полное название</label>
        <textarea name="full_name" id="full_name" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('full_name', $item->full_name) }}</textarea>
        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="study_form" class="block text-sm font-medium text-gray-700 mb-1">Форма обучения</label>
        <textarea name="study_form" id="study_form" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('study_form', $item->study_form) }}</textarea>
        @error('study_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<!-- Раздел 2: Учебная нагрузка (часы) -->
<h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">2. Учебная нагрузка (часы)</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div>
        <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">Всего часов</label>
        <input type="number" name="hours" id="hours" value="{{ old('hours', $item->hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="theory_hours" class="block text-sm font-medium text-gray-700 mb-1">Теоретическое обучение</label>
        <input type="number" name="theory_hours" id="theory_hours" value="{{ old('theory_hours', $item->theory_hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('theory_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="self_study_hours" class="block text-sm font-medium text-gray-700 mb-1">Теория с ДОТ / Самостоятельно</label>
        <input type="number" name="self_study_hours" id="self_study_hours" value="{{ old('self_study_hours', $item->self_study_hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('self_study_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Производственно-практическое обучение</label>
        <input type="number" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $item->practical_hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="practice_hours" class="block text-sm font-medium text-gray-700 mb-1">Практическое обучение</label>
        <input type="number" name="practice_hours" id="practice_hours" value="{{ old('practice_hours', $item->practice_hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('practice_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="distance_practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Практика с ЭО и ДОТ</label>
        <input type="number" step="0.01" name="distance_practical_hours" id="distance_practical_hours" value="{{ old('distance_practical_hours', $item->distance_practical_hours) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('distance_practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>
<!-- Раздел 3: Стоимость обучения без НДС (руб.) -->
<h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">3. Стоимость обучения без НДС (руб.)</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div>
        <label for="cost_total" class="block text-sm font-medium text-gray-700 mb-1">Итоговая стоимость без НДС</label>
        <input type="number" step="0.01" name="cost_total" id="cost_total" value="{{ old('cost_total', $item->cost_total) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_total') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="cost_fulltime_theory" class="block text-sm font-medium text-gray-700 mb-1">Очное теоретическое обучение</label>
        <input type="number" step="0.01" name="cost_fulltime_theory" id="cost_fulltime_theory" value="{{ old('cost_fulltime_theory', $item->cost_fulltime_theory) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_fulltime_theory') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="cost_distance_theory" class="block text-sm font-medium text-gray-700 mb-1">Теоретическое обучение с ДОТ</label>
        <input type="number" step="0.01" name="cost_distance_theory" id="cost_distance_theory" value="{{ old('cost_distance_theory', $item->cost_distance_theory) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_distance_theory') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="cost_industrial_practice" class="block text-sm font-medium text-gray-700 mb-1">Производственно-практическое</label>
        <input type="number" step="0.01" name="cost_industrial_practice" id="cost_industrial_practice" value="{{ old('cost_industrial_practice', $item->cost_industrial_practice) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_industrial_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="cost_fulltime_practice" class="block text-sm font-medium text-gray-700 mb-1">Очное практическое обучение</label>
        <input type="number" step="0.01" name="cost_fulltime_practice" id="cost_fulltime_practice" value="{{ old('cost_fulltime_practice', $item->cost_fulltime_practice) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_fulltime_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="cost_distance_practice" class="block text-sm font-medium text-gray-700 mb-1">Практическое обучение с ДОТ</label>
        <input type="number" step="0.01" name="cost_distance_practice" id="cost_distance_practice" value="{{ old('cost_distance_practice', $item->cost_distance_practice) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('cost_distance_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>
<!-- Раздел 4: Организационные параметры и заказчики -->
<h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">4. Организационные параметры и заказчики</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
        <label for="listener_category" class="block text-sm font-medium text-gray-700 mb-1">Категория слушателей</label>
        <input type="text" name="listener_category" id="listener_category" value="{{ old('listener_category', $item->listener_category) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('listener_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="group_size" class="block text-sm font-medium text-gray-700 mb-1">Наполняемость группы</label>
        <input type="text" name="group_size" id="group_size" value="{{ old('group_size', $item->group_size) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('group_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="control_form" class="block text-sm font-medium text-gray-700 mb-1">Форма аттестации</label>
        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $item->control_form) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('control_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="commission_type" class="block text-sm font-medium text-gray-700 mb-1">Тип комиссии</label>
        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $item->commission_type) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('commission_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Вид выдаваемого документа</label>
        <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $item->document_type) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('document_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="periodicity" class="block text-sm font-medium text-gray-700 mb-1">Периодичность обучения</label>
        <input type="text" name="periodicity" id="periodicity" value="{{ old('periodicity', $item->periodicity) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('periodicity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="uchipro" class="block text-sm font-medium text-gray-700 mb-1">Учи.Про</label>
        <input type="text" name="uchipro" id="uchipro" value="{{ old('uchipro', $item->uchipro) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('uchipro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="info_system" class="block text-sm font-medium text-gray-700 mb-1">Внесение в ИС</label>
        <input type="text" name="info_system" id="info_system" value="{{ old('info_system', $item->info_system) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('info_system') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="teacher_fio" class="block text-sm font-medium text-gray-700 mb-1">Преподаватель</label>
        <input type="text" name="teacher_fio" id="teacher_fio" value="{{ old('teacher_fio', $item->teacher_fio) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('teacher_fio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="only_yung" class="block text-sm font-medium text-gray-700 mb-1">Только для ЮНГ</label>
        <input type="text" name="only_yung" id="only_yung" value="{{ old('only_yung', $item->only_yung) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('only_yung') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="og" class="block text-sm font-medium text-gray-700 mb-1">ОГ</label>
        <input type="text" name="og" id="og" value="{{ old('og', $item->og) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('og') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="third_party" class="block text-sm font-medium text-gray-700 mb-1">Сторонние предприятия</label>
        <input type="text" name="third_party" id="third_party" value="{{ old('third_party', $item->third_party) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('third_party') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="site_publication" class="block text-sm font-medium text-gray-700 mb-1">Публикация на сайте</label>
        <input type="text" name="site_publication" id="site_publication" value="{{ old('site_publication', $item->site_publication) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('site_publication') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="inf_mo" class="block text-sm font-medium text-gray-700 mb-1">ИНФ МО</label>
        <input type="text" name="inf_mo" id="inf_mo" value="{{ old('inf_mo', $item->inf_mo) }}"
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        @error('inf_mo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Примечание</label>
        <textarea name="notes" id="notes" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes', $item->notes) }}</textarea>
        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="teacher_requirements" class="block text-sm font-medium text-gray-700 mb-1">Требования к преподавателям</label>
        <textarea name="teacher_requirements" id="teacher_requirements" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('teacher_requirements', $item->teacher_requirements) }}</textarea>
        @error('teacher_requirements') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Оборудование</label>
        <textarea name="equipment" id="equipment" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('equipment', $item->equipment) }}</textarea>
        @error('equipment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-3">
        <label for="equipment_location" class="block text-sm font-medium text-gray-700 mb-1">Местоположение оборудования</label>
        <textarea name="equipment_location" id="equipment_location" rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('equipment_location', $item->equipment_location) }}</textarea>
        @error('equipment_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
</div>
