<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование курса #{{ $course->id }}</h2>
            </div>

            <form action="{{ route('matrices.courses.update', $course) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="mb-4">
                        <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Категория программы</label>
                        <input type="text" name="program" id="program" value="{{ old('program', $course->program) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('program') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="number" class="block text-sm font-medium text-gray-700 mb-1">№ п/п</label>
                        <input type="number" name="number" id="number" value="{{ old('number', $course->number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Шифр</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="education_type" class="block text-sm font-medium text-gray-700 mb-1">Вид обучения</label>
                        <input type="text" name="education_type" id="education_type" value="{{ old('education_type', $course->education_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('education_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="program_name" class="block text-sm font-medium text-gray-700 mb-1">Наименование программы</label>
                        <textarea name="program_name" id="program_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('program_name', $course->program_name) }}</textarea>
                        @error('program_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Полное название</label>
                        <textarea name="full_name" id="full_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('full_name', $course->full_name) }}</textarea>
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="study_form" class="block text-sm font-medium text-gray-700 mb-1">Форма обучения</label>
                        <textarea name="study_form" id="study_form" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('study_form', $course->study_form) }}</textarea>
                        @error('study_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">Всего часов</label>
                        <input type="number" name="hours" id="hours" value="{{ old('hours', $course->hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="theory_hours" class="block text-sm font-medium text-gray-700 mb-1">Теория</label>
                        <input type="number" name="theory_hours" id="theory_hours" value="{{ old('theory_hours', $course->theory_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('theory_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="self_study_hours" class="block text-sm font-medium text-gray-700 mb-1">Самостоятельно</label>
                        <input type="number" name="self_study_hours" id="self_study_hours" value="{{ old('self_study_hours', $course->self_study_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('self_study_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Произв. практика</label>
                        <input type="number" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $course->practical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="practice_hours" class="block text-sm font-medium text-gray-700 mb-1">Практика</label>
                        <input type="number" name="practice_hours" id="practice_hours" value="{{ old('practice_hours', $course->practice_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('practice_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="listener_category" class="block text-sm font-medium text-gray-700 mb-1">Категория слушателей</label>
                        <input type="text" name="listener_category" id="listener_category" value="{{ old('listener_category', $course->listener_category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('listener_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="group_size" class="block text-sm font-medium text-gray-700 mb-1">Группа (чел)</label>
                        <input type="text" name="group_size" id="group_size" value="{{ old('group_size', $course->group_size) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('group_size') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="control_form" class="block text-sm font-medium text-gray-700 mb-1">Форма контроля</label>
                        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $course->control_form) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('control_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="commission_type" class="block text-sm font-medium text-gray-700 mb-1">Тип комиссии</label>
                        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $course->commission_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('commission_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">Документ</label>
                        <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $course->document_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('document_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Примечание</label>
                        <textarea name="notes" id="notes" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('notes', $course->notes) }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="uchipro" class="block text-sm font-medium text-gray-700 mb-1">Учи.Про</label>
                        <input type="text" name="uchipro" id="uchipro" value="{{ old('uchipro', $course->uchipro) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('uchipro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="info_system" class="block text-sm font-medium text-gray-700 mb-1">Инф. система</label>
                        <input type="text" name="info_system" id="info_system" value="{{ old('info_system', $course->info_system) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('info_system') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="teacher_requirements" class="block text-sm font-medium text-gray-700 mb-1">Требования к преподавателям</label>
                        <textarea name="teacher_requirements" id="teacher_requirements" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('teacher_requirements', $course->teacher_requirements) }}</textarea>
                        @error('teacher_requirements') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Оборудование</label>
                        <textarea name="equipment" id="equipment" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('equipment', $course->equipment) }}</textarea>
                        @error('equipment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="equipment_location" class="block text-sm font-medium text-gray-700 mb-1">Местоположение оборудования</label>
                        <textarea name="equipment_location" id="equipment_location" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('equipment_location', $course->equipment_location) }}</textarea>
                        @error('equipment_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="teacher_fio" class="block text-sm font-medium text-gray-700 mb-1">Преподаватель</label>
                        <input type="text" name="teacher_fio" id="teacher_fio" value="{{ old('teacher_fio', $course->teacher_fio) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('teacher_fio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('matrices.courses.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>