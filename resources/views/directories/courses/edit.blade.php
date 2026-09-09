<x-layouts.app-with-sidebar>
    <div class="max-w-3xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование курса</h2>
            </div>

            <form action="{{ route('directories.courses.update', $course) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="option_value" class="block text-sm font-medium text-gray-700 mb-2">Option Value</label>
                    <input type="number" 
                           name="option_value" 
                           id="option_value" 
                           value="{{ old('option_value', $course->option_value) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('option_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Название курса</label>
                    <textarea name="course" 
                              id="course" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              required>{{ old('course', $course->course) }}</textarea>
                    @error('course')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('directories.courses.index') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app-with-sidebar>