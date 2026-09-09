<x-layouts.app-with-sidebar>
    <div class="max-w-4xl mx-auto my-6">
        <!-- Кнопка возврата -->
        <div class="mb-4">
            <a href="{{ route('demands.index') }}" class="text-sm text-indigo-600 hover:underline">← Вернуться к реестру</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-900 text-white px-6 py-4">
                <h2 class="text-lg font-bold">Детальная информация по заявке #{{ $demand->dem_id }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">Полный дамп системных и бизнес-полей из PostgreSQL</p>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <!-- Блок 1 -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2 border">
                    <h3 class="font-bold text-gray-700 border-b pb-1 mb-2">Бизнес-параметры</h3>
                    <div><span class="text-gray-400">Номер заявки:</span> <strong class="text-gray-900">{{ $demand->dem_num }}</strong></div>
                    <div><span class="text-gray-400">Год:</span> {{ $demand->dem_year }}</div>
                    <div><span class="text-gray-400">Курс:</span> {{ $demand->course->course_name ?? 'ID: '.$demand->id_course }}</div>
                    <div><span class="text-gray-400">Учебное заведение:</span> {{ $demand->vuz->vuz_name ?? 'ID: '.$demand->id_vuz }}</div>
                    <div><span class="text-gray-400">Профессия:</span> {{ $demand->prof->prof_name ?? 'ID: '.$demand->id_prof }}</div>
                    <div><span class="text-gray-400">Период:</span> с {{ $demand->date_start }} по {{ $demand->date_end }}</div>
                </div>

                <!-- Блок 2 -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2 border">
                    <h3 class="font-bold text-gray-700 border-b pb-1 mb-2">Служебные и системные поля</h3>
                    <div><span class="text-gray-400">Статус (код):</span> <span class="badge bg-gray-200 px-2 py-0.5 rounded text-xs">{{ $demand->dem_status }}</span></div>
                    <div><span class="text-gray-400">Плановая (isplanned):</span> {{ $demand->isplanned ? 'Да (1)' : 'Нет (0)' }}</div>
                    <div><span class="text-gray-400">Является сотрудником:</span> {{ $demand->isworker }}</div>
                    <div><span class="text-gray-400">Версия строки (row_version):</span> {{ $demand->row_verion }}</div>
                    <div><span class="text-gray-400">Разовый (isdisposable):</span> {{ $demand->isdisposable }}</div>
                    <div><span class="text-gray-400">ID автора курса (idauthor):</span> {{ $demand->idauthor ?? 'NULL' }}</div>
                </div>

                <!-- Блок 3 -->
                <div class="bg-gray-50 p-4 rounded-lg space-y-2 border md:col-span-2">
                    <h3 class="font-bold text-gray-700 border-b pb-1 mb-2">Логирование и сессии</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="text-gray-400">Дата создания:</span> {{ $demand->dem_date }}</div>
                        <div><span class="text-gray-400">ID Создателя:</span> {{ $demand->id_user_create }} ({{ $demand->creator->user_login ?? 'н/д' }})</div>
                        <div><span class="text-gray-400">Дата изменения:</span> {{ $demand->date_edit }}</div>
                        <div><span class="text-gray-400">ID Редактора:</span> {{ $demand->id_user_edit }} ({{ $demand->editor->user_login ?? 'н/д' }})</div>
                        <div class="col-span-2"><span class="text-gray-400">Текущий Session ID:</span> <code class="bg-white p-1 rounded border text-[11px] block mt-1 truncate">{{ $demand->sessionid ?? 'NULL' }}</code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>
