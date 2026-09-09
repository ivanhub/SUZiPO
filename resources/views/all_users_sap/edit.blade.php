<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование пользователя SAP #{{ $allUserSap->id }}</h2>
            </div>

            <form action="{{ route('all-users-sap.update', $allUserSap) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="mb-4">
                        <label for="tab_number" class="block text-sm font-medium text-gray-700 mb-1">Таб. номер</label>
                        <input type="number" name="tab_number" id="tab_number" value="{{ old('tab_number', $allUserSap->tab_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('tab_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $allUserSap->last_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $allUserSap->first_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Отчество</label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $allUserSap->middle_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('middle_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Дата рождения</label>
                        <input type="date" name="birth_date" id="birth_date" 
                               value="{{ old('birth_date', $allUserSap->birth_date ? $allUserSap->birth_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('birth_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Пол</label>
                        <select name="gender" id="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Выберите пол</option>
                            <option value="1" {{ old('gender', $allUserSap->gender) == '1' ? 'selected' : '' }}>Мужской</option>
                            <option value="2" {{ old('gender', $allUserSap->gender) == '2' ? 'selected' : '' }}>Женский</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="gender_key" class="block text-sm font-medium text-gray-700 mb-1">Ключ пола</label>
                        <input type="text" name="gender_key" id="gender_key" value="{{ old('gender_key', $allUserSap->gender_key) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('gender_key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="pfr_certificate" class="block text-sm font-medium text-gray-700 mb-1">Свидетельство ПФР</label>
                        <input type="text" name="pfr_certificate" id="pfr_certificate" value="{{ old('pfr_certificate', $allUserSap->pfr_certificate) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('pfr_certificate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Должность</label>
                        <input type="text" name="position" id="position" value="{{ old('position', $allUserSap->position) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="rank" class="block text-sm font-medium text-gray-700 mb-1">Разряд</label>
                        <input type="text" name="rank" id="rank" value="{{ old('rank', $allUserSap->rank) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('rank') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="level_4_name" class="block text-sm font-medium text-gray-700 mb-1">Уровень 4</label>
                        <input type="text" name="level_4_name" id="level_4_name" value="{{ old('level_4_name', $allUserSap->level_4_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('level_4_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="level_3_name" class="block text-sm font-medium text-gray-700 mb-1">Уровень 3</label>
                        <input type="text" name="level_3_name" id="level_3_name" value="{{ old('level_3_name', $allUserSap->level_3_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('level_3_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="duv_b" class="block text-sm font-medium text-gray-700 mb-1">ДУвБ</label>
                        <input type="text" name="duv_b" id="duv_b" value="{{ old('duv_b', $allUserSap->duv_b) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('duv_b') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="mvz" class="block text-sm font-medium text-gray-700 mb-1">МВЗ</label>
                        <input type="text" name="mvz" id="mvz" value="{{ old('mvz', $allUserSap->mvz) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('mvz') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="employee_category" class="block text-sm font-medium text-gray-700 mb-1">Категория сотрудника</label>
                        <input type="text" name="employee_category" id="employee_category" value="{{ old('employee_category', $allUserSap->employee_category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('employee_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <a href="{{ route('all-users-sap.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>