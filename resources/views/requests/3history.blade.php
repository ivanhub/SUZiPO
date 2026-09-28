<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">История изменений заявки #{{ $request->req_id }}</h2>
                    <a href="{{ route('requests.show', $request->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">← Назад к заявке</a>
                </div>

                @if($allActivities->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изменение</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($allActivities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity->causer->name ?? 'Система' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900">
                                        @if($activity->subject_type === \App\Models\Request::class)
                                            <span class="text-blue-600">Изменение заявки</span>
                                        @elseif($activity->subject_type === \App\Models\RequestEmployee::class)
                                            <span class="text-green-600">Изменение сотрудника</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500">
                                        @if($activity->log_name === 'default')
                                            @if($activity->description === 'created')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Добавление</span>
                                            @elseif($activity->description === 'updated')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Обновление</span>
                                            @elseif($activity->description === 'deleted')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Удаление</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $activity->description }}</span>
                                            @endif
                                        @else
                                            {{ $activity->description }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <!-- Кнопка просмотра -->
                                            <button type="button" 
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    onclick="showActivityDetails({{ $activity->id }})">
                                                👁️
                                            </button>
                                            
                                            <!-- Кнопка отката -->
                                            <form action="{{ route('requests.rollback', [$request->id, $activity->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-orange-600 hover:text-orange-900"
                                                        onclick="return confirm('Откатить изменения? Это вернет значения полей к состоянию до этого изменения.')">
                                                    ↩️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $allActivities->links() }}
                    </div>
                @else
                    <p class="text-sm text-gray-500">История изменений пуста</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Модальное окно просмотра изменений -->
    <div id="activityModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-gray-500 opacity-75" onclick="closeActivityModal()"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Детали изменения</h3>
                    <button onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div id="activityDetails" class="text-sm">
                    <!-- Сюда будет загружаться содержимое -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Данные активности
        const activitiesData = @json($allActivities->items());
        
        const fieldLabels = {
            'start_date': 'Дата начала обучения',
            'end_date': 'Дата окончания обучения',
            'issue_date': 'Дата оформления',
            'course_id': 'Курс',
            'provider_id': 'Провайдер',
            'audience_id': 'Аудитория',
            'teacher_id': 'Преподаватель',
            'curator_id': 'Куратор',
            'status': 'Статус',
            'education_form': 'Форма образования',
            'employee_type': 'ИТР/рабочие',
            'production_break': 'С отрывом от производства',
            'country': 'Страна',
            'city_id': 'Город',
            'profession_id': 'Профессия',
            'cost_profit': 'Себестоимость/Прибыль',
            
            // Поля сотрудника
            'tab_number': 'Таб. номер',
            'last_name': 'Фамилия',
            'first_name': 'Имя',
            'middle_name': 'Отчество',
            'position': 'Должность',
            'absence_start_date': 'Дата начала отсутствия',
            'absence_end_date': 'Дата окончания отсутствия',
            'absence_reason': 'Причина отсутствия',
            'absence_type': 'Форма обучения',
            'note': 'Примечание',
            'document_issue_date': 'Дата выдачи документа',
            'reissue_period': 'Периодичность',
        };

        function showActivityDetails(activityId) {
            const activity = activitiesData.find(a => a.id === activityId);
            if (!activity) return;
            
            let html = '';
            
            if (activity.subject_type === 'App\\Models\\Request') {
                html += '<p class="font-semibold text-blue-600 mb-2">Изменение заявки</p>';
            } else if (activity.subject_type === 'App\\Models\\RequestEmployee') {
                html += '<p class="font-semibold text-green-600 mb-2">Изменение сотрудника</p>';
            }
            
            html += '<p class="text-gray-500 mb-2">' + 
                '<strong>Дата:</strong> ' + new Date(activity.created_at).toLocaleString('ru-RU') + '<br>' +
                '<strong>Пользователь:</strong> ' + (activity.causer?.name || 'Система') + 
                '</p>';
            
            if (activity.attribute_changes && activity.attribute_changes.old) {
                html += '<div class="mt-4 border-t border-gray-200 pt-4">';
                html += '<h4 class="font-semibold mb-2">Измененные поля:</h4>';
                
                for (const [field, oldValue] of Object.entries(activity.attribute_changes.old)) {
                    const newValue = activity.attribute_changes.attributes[field] ?? null;
                    const label = fieldLabels[field] || field;
                    
                    html += '<div class="flex justify-between py-1 border-b border-gray-100">';
                    html += '<span class="text-gray-500">' + label + ':</span>';
                    html += '<span class="ml-4">';
                    html += '<span class="text-red-600 line-through">' + (oldValue || '—') + '</span>';
                    html += ' → ';
                    html += '<span class="text-green-600">' + (newValue || '—') + '</span>';
                    html += '</span>';
                    html += '</div>';
                }
                
                html += '</div>';
            }
            
            document.getElementById('activityDetails').innerHTML = html;
            document.getElementById('activityModal').classList.remove('hidden');
        }

        function closeActivityModal() {
            document.getElementById('activityModal').classList.add('hidden');
        }
    </script>
</x-layouts.app-with-sidebar>