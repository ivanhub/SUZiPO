<x-layouts.app-with-sidebar>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Заявки на обучение</h2>
                    <a href="{{ route('requests.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        + Создать заявку
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed; width: 100%;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 40px;">ID</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Статус</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 55px;">Разов.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 85px;">Дата нач.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 85px;">Дата окон.</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">Курс</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">Провайдер</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Автор</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 75px;">Создана</th>
                                <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 85px;">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                                <td class="px-1 py-4 whitespace-nowrap">
                                    <span class="px-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($request->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'sent') bg-blue-100 text-blue-800
                                        @elseif($request->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->one_time ? 'Да' : 'Нет' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->start_date ? $request->start_date->format('d.m') : '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->end_date ? $request->end_date->format('d.m') : '—' }}</td>
                                <td class="px-1 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->course->course ?? '' }}">{{ Str::limit($request->course->course ?? '—', 40) }}</td>
                                <td class="px-1 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $request->provider->name ?? '' }}">{{ Str::limit($request->provider->name ?? '—', 40) }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis;" title="{{ $request->user->name ?? '' }}">{{ $request->user->name ?? '—' }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('d.m.Y') }}</td>
                                <td class="px-1 py-4 whitespace-nowrap text-sm font-medium" style="white-space: nowrap;">
                                    <a href="{{ route('requests.show', $request) }}" class="text-blue-600 hover:text-blue-900" title="Просмотр">👁️</a>
                                    <a href="{{ route('requests.edit', $request) }}" class="text-indigo-600 hover:text-indigo-900" title="Редактировать">✏️</a>
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить"
                                                onclick="return confirm('Удалить заявку #{{ $request->id }}?')">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-1 py-4 text-center text-sm text-gray-500">Заявок пока нет</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $requests->links() }}
                </div>

            </div>
        </div>
    </div>
</x-layouts.app-with-sidebar>