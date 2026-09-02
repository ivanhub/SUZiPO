<x-layouts.app-with-sidebar>
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Система управления заявками и протоколами обучения (СУЗиПО)
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Система предназначена для учёта заявок и протоколов обучения, контроля их своевременного исполнения. 
                Система СУЗиПО взаимодействует с СУП "БОСС-Кадровик" в части получения общей информации о работнике 
                и занесения информации о фактическом обучении на основе протоколов обучения.
            </p>
            <p class="mt-2 text-sm text-gray-400">Текущая версия 0.1</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Заявки -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full">+12%</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">156</h3>
                <p class="text-sm text-gray-500 mt-1">Всего заявок</p>
            </div>

            <!-- Протоколы -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-full bg-green-50 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-green-600 bg-green-50 px-2.5 py-0.5 rounded-full">+8%</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">89</h3>
                <p class="text-sm text-gray-500 mt-1">Протоколов</p>
            </div>

            <!-- Ожидают -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-full bg-yellow-50 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-yellow-600 bg-yellow-50 px-2.5 py-0.5 rounded-full">23</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">34</h3>
                <p class="text-sm text-gray-500 mt-1">Ожидают обработки</p>
            </div>

            <!-- Обучено -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-purple-600 bg-purple-50 px-2.5 py-0.5 rounded-full">1,245</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">2,847</h3>
                <p class="text-sm text-gray-500 mt-1">Обучено сотрудников</p>
            </div>
        </div>

        <!-- Recent Requests Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Последние заявки</h2>
                <a href="{{ route('requests.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Все заявки →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сотрудник</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Курс</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentRequests ?? [] as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->employee_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->course_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($request->status === 'draft') bg-yellow-100 text-yellow-800
                                    @elseif($request->status === 'sent') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at ? $request->created_at->format('d.m.Y') : '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                Нет последних заявок
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('requests.create') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all duration-200 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Создать заявку</h3>
                        <p class="text-xs text-gray-500 mt-1">Новая заявка на обучение</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('bookings.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all duration-200 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-green-50 text-green-600 group-hover:bg-green-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Забронировать аудиторию</h3>
                        <p class="text-xs text-gray-500 mt-1">Бронирование на дату</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('protocols.create') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-indigo-300 transition-all duration-200 group">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-purple-50 text-purple-600 group-hover:bg-purple-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Новый протокол</h3>
                        <p class="text-xs text-gray-500 mt-1">Создать протокол обучения</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app-with-sidebar>