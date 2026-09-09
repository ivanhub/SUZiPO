<x-layouts.app-with-sidebar>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование преподавателя</h2>
            </div>

            <form action="{{ route('directories.teachers.update', $teacher) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="fio" class="block text-sm font-medium text-gray-700 mb-2">ФИО *</label>
                    <input type="text" 
                           name="fio" 
                           id="fio" 
                           value="{{ old('fio', $teacher->fio) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           required>
                    @error('fio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Профессия</label>
                    <input type="text" 
                           name="profession" 
                           id="profession" 
                           value="{{ old('profession', $teacher->profession) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('profession')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="division1" class="block text-sm font-medium text-gray-700 mb-2">Division 1</label>
                        <input type="text" 
                               name="division1" 
                               id="division1" 
                               value="{{ old('division1', $teacher->division1) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="division2" class="block text-sm font-medium text-gray-700 mb-2">Division 2</label>
                        <input type="text" 
                               name="division2" 
                               id="division2" 
                               value="{{ old('division2', $teacher->division2) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="division3" class="block text-sm font-medium text-gray-700 mb-2">Division 3</label>
                        <input type="text" 
                               name="division3" 
                               id="division3" 
                               value="{{ old('division3', $teacher->division3) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('directories.teachers.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>