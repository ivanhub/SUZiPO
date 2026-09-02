<x-layouts.app-with-sidebar>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование аудитории</h2>
            </div>

            <form action="{{ route('directories.audiences.update', $audience) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-2">Номер аудитории *</label>
                    <input type="text" 
                           name="number" 
                           id="number" 
                           value="{{ old('number', $audience->number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           required>
                    @error('number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Место нахождения аудитории</label>
                    <input type="text" 
                           name="location" 
                           id="location" 
                           value="{{ old('location', $audience->location) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="responsible_person" class="block text-sm font-medium text-gray-700 mb-2">Ответственное лицо</label>
                    <input type="text" 
                           name="responsible_person" 
                           id="responsible_person" 
                           value="{{ old('responsible_person', $audience->responsible_person) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('responsible_person')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="seats" class="block text-sm font-medium text-gray-700 mb-2">Количество посадочных мест</label>
                    <input type="text" 
                           name="seats" 
                           id="seats" 
                           value="{{ old('seats', $audience->seats) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('seats')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('directories.audiences.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>