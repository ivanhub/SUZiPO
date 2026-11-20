<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto py-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Редактирование ОТ #{{ $matrixOt->id }}</h2>
            </div>

            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('matrices.ot.update', $matrixOt) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @include('matrices.ot._form', ['matrixOt' => $matrixOt])

                <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('matrices.ot.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">Отмена</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('error') || $errors->any())
        <script>
            console.error('Ошибка сохранения ОТ:', { sessionError: @json(session('error')), validationErrors: @json($errors->all()) });
        </script>
    @endif
</x-layouts.app-with-sidebar>
