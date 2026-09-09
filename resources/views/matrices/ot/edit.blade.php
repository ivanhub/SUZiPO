<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование ОТ #{{ $matrixOt->id }}</h2>
            </div>

            <form action="{{ route('matrices.ot.update', $matrixOt) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="mb-4">
                        <label for="row_number" class="block text-sm font-medium text-gray-700 mb-1">№ п/п</label>
                        <input type="number" name="row_number" id="row_number" value="{{ old('row_number', $matrixOt->row_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('row_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Шифр</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $matrixOt->code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="training_type" class="block text-sm font-medium text-gray-700 mb-1">Тип обучения</label>
                        <input type="text" name="training_type" id="training_type" value="{{ old('training_type', $matrixOt->training_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('training_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="program_name" class="block text-sm font-medium text-gray-700 mb-1">Наименование программы</label>
                        <textarea name="program_name" id="program_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('program_name', $matrixOt->program_name) }}</textarea>
                        @error('program_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Полное название</label>
                        <textarea name="full_name" id="full_name" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('full_name', $matrixOt->full_name) }}</textarea>
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="study_form" class="block text-sm font-medium text-gray-700 mb-1">Форма обучения</label>
                        <textarea name="study_form" id="study_form" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('study_form', $matrixOt->study_form) }}</textarea>
                        @error('study_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_hours" class="block text-sm font-medium text-gray-700 mb-1">Всего часов</label>
                        <input type="number" name="total_hours" id="total_hours" value="{{ old('total_hours', $matrixOt->total_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('total_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="fulltime_theoretical_hours" class="block text-sm font-medium text-gray-700 mb-1">Теория (очно)</label>
                        <input type="number" step="0.01" name="fulltime_theoretical_hours" id="fulltime_theoretical_hours" value="{{ old('fulltime_theoretical_hours', $matrixOt->fulltime_theoretical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('fulltime_theoretical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="distance_theoretical_hours" class="block text-sm font-medium text-gray-700 mb-1">Теория (дистанционно)</label>
                        <input type="number" step="0.01" name="distance_theoretical_hours" id="distance_theoretical_hours" value="{{ old('distance_theoretical_hours', $matrixOt->distance_theoretical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('distance_theoretical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="industrial_practice_hours" class="block text-sm font-medium text-gray-700 mb-1">Производственная практика</label>
                        <input type="number" name="industrial_practice_hours" id="industrial_practice_hours" value="{{ old('industrial_practice_hours', $matrixOt->industrial_practice_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('industrial_practice_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="practical_hours" class="block text-sm font-medium text-gray-700 mb-1">Практическое обучение</label>
                        <input type="number" step="0.01" name="practical_hours" id="practical_hours" value="{{ old('practical_hours', $matrixOt->practical_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('practical_hours') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="student_category" class="block text-sm font-medium text-gray-700 mb-1">Категория слушателей</label>
                        <input type="text" name="student_category" id="student_category" value="{{ old('student_category', $matrixOt->student_category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('student_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="group_capacity" class="block text-sm font-medium text-gray-700 mb-1">Наполняемость группы</label>
                        <input type="text" name="group_capacity" id="group_capacity" value="{{ old('group_capacity', $matrixOt->group_capacity) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('group_capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="control_form" class="block text-sm font-medium text-gray-700 mb-1">Форма контроля</label>
                        <input type="text" name="control_form" id="control_form" value="{{ old('control_form', $matrixOt->control_form) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('control_form') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="commission_type" class="block text-sm font-medium text-gray-700 mb-1">Тип комиссии</label>
                        <input type="text" name="commission_type" id="commission_type" value="{{ old('commission_type', $matrixOt->commission_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('commission_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="issued_document" class="block text-sm font-medium text-gray-700 mb-1">Вид выдаваемого документа</label>
                        <input type="text" name="issued_document" id="issued_document" value="{{ old('issued_document', $matrixOt->issued_document) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('issued_document') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Примечание</label>
                        <textarea name="note" id="note" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('note', $matrixOt->note) }}</textarea>
                        @error('note') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="uchi_pro" class="block text-sm font-medium text-gray-700 mb-1">Учи.Про</label>
                        <input type="text" name="uchi_pro" id="uchi_pro" value="{{ old('uchi_pro', $matrixOt->uchi_pro) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('uchi_pro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-2">
                        <label for="information_system_entry" class="block text-sm font-medium text-gray-700 mb-1">Внесение в ИС</label>
                        <textarea name="information_system_entry" id="information_system_entry" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('information_system_entry', $matrixOt->information_system_entry) }}</textarea>
                        @error('information_system_entry') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="equipment" class="block text-sm font-medium text-gray-700 mb-1">Оборудование</label>
                        <textarea name="equipment" id="equipment" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('equipment', $matrixOt->equipment) }}</textarea>
                        @error('equipment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 md:col-span-3">
                        <label for="equipment_location" class="block text-sm font-medium text-gray-700 mb-1">Местоположение оборудования</label>
                        <textarea name="equipment_location" id="equipment_location" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('equipment_location', $matrixOt->equipment_location) }}</textarea>
                        @error('equipment_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="teacher_name" class="block text-sm font-medium text-gray-700 mb-1">Преподаватель</label>
                        <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $matrixOt->teacher_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('teacher_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code_ucjung" class="block text-sm font-medium text-gray-700 mb-1">Код ЮНГ</label>
                        <input type="text" name="code_ucjung" id="code_ucjung" value="{{ old('code_ucjung', $matrixOt->code_ucjung) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('code_ucjung') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="code_ul" class="block text-sm font-medium text-gray-700 mb-1">Код ЮЛ</label>
                        <input type="text" name="code_ul" id="code_ul" value="{{ old('code_ul', $matrixOt->code_ul) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('code_ul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('matrices.ot.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>