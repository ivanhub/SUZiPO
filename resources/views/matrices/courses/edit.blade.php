<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование курса #{{ $course->id }}</h2>
            </div>

            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('matrices.courses.update', $course) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Раздел 1: Основные реквизиты, реестр и сроки действия -->
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">1. Идентификация программы и сроки действия</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Категория программы</label>
                        <input type="text" name="program" id="program" value="{{ old('program', $course->program) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('program') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="number" class="block text-sm font-medium text-gray-700 mb-1">№ п/п</label>
                        <input type="number" name="number" id="number" value="{{ old('number', $course->number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Шифр</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="sro_education_type" class="block text-sm font-medium text-gray-700 mb-1">Вид обучения в СРО</label>
                        <input type="text" name="sro_education_type" id="sro_education_type" value="{{ old('sro_education_type', $course->sro_education_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('sro_education_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="registry_number" class="block text-sm font-medium text-gray-700 mb-1">№ Программы по реестру</label>
                        <input type="text" name="registry_number" id="registry_number" value="{{ old('registry_number', $course->registry_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('registry_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="additional_code" class="block text-sm font-medium text-gray-700 mb-1">Дополнительный код</label>
                        <input type="text" name="additional_code" id="additional_code" value="{{ old('additional_code', $course->additional_code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('additional_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Дата введения в действие</label>
                        <input type="date" name="date_start" id="date_start" value="{{ old('date_start', $course->date_start ? \Carbon\Carbon::parse($course->date_start)->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('date_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Дата окончания действия</label>
                        <input type="date" name="date_end" id="date_end" value="{{ old('date_end', $course->date_end ? \Carbon\Carbon::parse($course->date_end)->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('date_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="education_type" class="block text-sm font-medium text-gray-700 mb-1">Вид обучения</label>
                        <input type="text" name="education_type" id="education_type" value="{{ old('education_type', $course->education_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('education_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="program_name" class="block text-sm font-medium text-gray-700 mb-1">Наименование программы</label>
                        <textarea name="program_name" id="program_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('program_name', $course->program_name) }}</textarea>
                        @error('program_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Полное название</label>
                        <textarea name="full_name" id="full_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('full_name', $course->full_name) }}</textarea>
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="study_form" class="block text-sm font-medium text-gray-700 mb-1">Форма обучения</label>
                        <textarea name="study_form" id="study_form" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('study_form', $course->study_form) }}</textarea>
                        @error('study_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <!-- Раздел 2: Учебная нагрузка (часы) -->
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">2. Учебная нагрузка (часы)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">Всего часов</label>
                        <input type="number" name="hours" id="hours" value="{{ old('hours', $course->hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="theory_hours" class="block text-sm font-medium text-gray-700 mb-1">Очное теоретическое обучение</label>
                        <input type="number" name="theory_hours" id="theory_hours" value="{{ old('theory_hours', $course->theory_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('theory_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="self_study_hours" class="block text-sm font-medium text-gray-700 mb-1">Теория с ДОТ / Самостоятельно</label>
                        <input type="number" name="self_study_hours" id="self_study_hours" value="{{ old('self_study_hours', $course->self_study_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('self_study_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Произв. практика</label>
                        <input type="number" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $course->practical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="practice_hours" class="block text-sm font-medium text-gray-700 mb-1">Очная практика</label>
                        <input type="number" name="practice_hours" id="practice_hours" value="{{ old('practice_hours', $course->practice_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('practice_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="distance_practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Практика с ЭО и ДОТ</label>
                        <input type="number" step="0.01" name="distance_practical_hours" id="distance_practical_hours" value="{{ old('distance_practical_hours', $course->distance_practical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('distance_practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Раздел 3: Стоимость обучения (руб.) -->
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">3. Стоимость обучения без НДС (руб.)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="cost_total" class="block text-sm font-medium text-gray-700 mb-1">Итоговая стоимость без НДС</label>
                        <input type="number" step="0.01" name="cost_total" id="cost_total" value="{{ old('cost_total', $course->cost_total) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_total') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cost_fulltime_theory" class="block text-sm font-medium text-gray-700 mb-1">Очное теоретическое обучение</label>
                        <input type="number" step="0.01" name="cost_fulltime_theory" id="cost_fulltime_theory" value="{{ old('cost_fulltime_theory', $course->cost_fulltime_theory) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_fulltime_theory') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cost_distance_theory" class="block text-sm font-medium text-gray-700 mb-1">Теоретическое обучение с ДОТ</label>
                        <input type="number" step="0.01" name="cost_distance_theory" id="cost_distance_theory" value="{{ old('cost_distance_theory', $course->cost_distance_theory) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_distance_theory') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cost_industrial_practice" class="block text-sm font-medium text-gray-700 mb-1">Производственно-практическое</label>
                        <input type="number" step="0.01" name="cost_industrial_practice" id="cost_industrial_practice" value="{{ old('cost_industrial_practice', $course->cost_industrial_practice) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_industrial_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cost_fulltime_practice" class="block text-sm font-medium text-gray-700 mb-1">Очное практическое обучение</label>
                        <input type="number" step="0.01" name="cost_fulltime_practice" id="cost_fulltime_practice" value="{{ old('cost_fulltime_practice', $course->cost_fulltime_practice) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_fulltime_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cost_distance_practice" class="block text-sm font-medium text-gray-700 mb-1">Практическое обучение с ДОТ</label>
                        <input type="number" step="0.01" name="cost_distance_practice" id="cost_distance_practice" value="{{ old('cost_distance_practice', $course->cost_distance_practice) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('cost_distance_practice') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <!-- Раздел 4: Организационные параметры и заказчики -->
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 pb-2 border-b border-gray-100">4. Организационные параметры и заказчики</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="listener_category" class="block text-sm font-medium text-gray-700 mb-1">Категория слушателей</label>
                        <input type="text" name="listener_category" id="listener_category" value="{{ old('listener_category', $course->listener_category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('listener_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="group_size" class="block text-sm font-medium text-gray-700 mb-1">Группа (чел)</label>
                        <input type="text" name="group_size" id="group_size" value="{{ old('group_size', $course->group_size) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('group_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="control_form" class="block text-sm font-medium text-gray-700 mb-1">Форма контроля</label>
                        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $course->control_form) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('control_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="commission_type" class="block text-sm font-medium text-gray-700 mb-1">Тип комиссии</label>
                        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $course->commission_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('commission_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Документ</label>
                        <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $course->document_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('document_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="periodicity" class="block text-sm font-medium text-gray-700 mb-1">Периодичность обучения</label>
                        <input type="text" name="periodicity" id="periodicity" value="{{ old('periodicity', $course->periodicity) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('periodicity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="uchipro" class="block text-sm font-medium text-gray-700 mb-1">Учи.Про</label>
                        <input type="text" name="uchipro" id="uchipro" value="{{ old('uchipro', $course->uchipro) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('uchipro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="info_system" class="block text-sm font-medium text-gray-700 mb-1">Инф. система</label>
                        <input type="text" name="info_system" id="info_system" value="{{ old('info_system', $course->info_system) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('info_system') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="teacher_fio" class="block text-sm font-medium text-gray-700 mb-1">Преподаватель</label>
                        <input type="text" name="teacher_fio" id="teacher_fio" value="{{ old('teacher_fio', $course->teacher_fio) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('teacher_fio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="only_yung" class="block text-sm font-medium text-gray-700 mb-1">Только для ЮНГ</label>
                        <input type="text" name="only_yung" id="only_yung" value="{{ old('only_yung', $course->only_yung) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('only_yung') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="og" class="block text-sm font-medium text-gray-700 mb-1">ОГ</label>
                        <input type="text" name="og" id="og" value="{{ old('og', $course->og) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('og') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="third_party" class="block text-sm font-medium text-gray-700 mb-1">Сторонние предприятия</label>
                        <input type="text" name="third_party" id="third_party" value="{{ old('third_party', $course->third_party) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('third_party') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="site_publication" class="block text-sm font-medium text-gray-700 mb-1">Публикация на сайте</label>
                        <input type="text" name="site_publication" id="site_publication" value="{{ old('site_publication', $course->site_publication) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('site_publication') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="inf_mo" class="block text-sm font-medium text-gray-700 mb-1">ИНФ МО</label>
                        <input type="text" name="inf_mo" id="inf_mo" value="{{ old('inf_mo', $course->inf_mo) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('inf_mo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Примечание</label>
                        <textarea name="notes" id="notes" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes', $course->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="teacher_requirements" class="block text-sm font-medium text-gray-700 mb-1">Требования к преподавателям</label>
                        <textarea name="teacher_requirements" id="teacher_requirements" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('teacher_requirements', $course->teacher_requirements) }}</textarea>
                        @error('teacher_requirements') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Оборудование</label>
                        <textarea name="equipment" id="equipment" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('equipment', $course->equipment) }}</textarea>
                        @error('equipment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label for="equipment_location" class="block text-sm font-medium text-gray-700 mb-1">Местоположение оборудования</label>
                        <textarea name="equipment_location" id="equipment_location" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('equipment_location', $course->equipment_location) }}</textarea>
                        @error('equipment_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('matrices.courses.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
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
