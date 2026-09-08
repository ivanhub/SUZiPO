<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Редактирование курса-исключения</h2>

                <form action="{{ route('course-exceptions.update', $courseException) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="course_name" class="block text-sm font-medium text-gray-700 mb-1">Название курса</label>
                        <input type="text" name="course_name" id="course_name" value="{{ old('course_name', $courseException->course_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               required>
                        @error('course_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <a href="{{ route('course-exceptions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>