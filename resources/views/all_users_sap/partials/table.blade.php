<div id="tableContainer">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;" id="requestsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 60px;">Таб.№</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">ФИО</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Дата рождения</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Пол</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Должность</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Подразделение</th>
                    <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->tab_number ?? '—' }}</td>
                    <td class="px-2 py-4 text-sm text-gray-900" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->full_name }}">{{ $user->full_name ?? '—' }}</td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->birth_date ? $user->birth_date->format('d.m.Y') : '—' }}</td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->gender ?? '—' }}</td>
                    <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->position }}">{{ $user->position ?? '—' }}</td>
                    <td class="px-2 py-4 text-sm text-gray-500" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $user->level_4_name }}">{{ $user->level_4_name ?? '—' }}</td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('all-users-sap.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">✏️</a>
                        <form action="{{ route('all-users-sap.delete', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Удалить?')">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-2 py-4 text-center text-sm text-gray-500">Записей не найдено</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
