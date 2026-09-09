@forelse($demands as $demand)
    <tr class="hover:bg-gray-50/80 transition-colors border-b text-xs">
        <!-- 1. ID -->
        <td class="px-3 py-2 font-bold text-indigo-600">#{{ $demand->dem_id }}</td>
        
        <!-- 2. № / Год -->
        <td class="px-3 py-2">
            <span class="block font-semibold text-gray-900">{{ $demand->dem_num ?? '—' }}</span>
            <span class="block text-gray-400 text-[10px]">Год: {{ $demand->dem_year }}</span>
        </td>
        
        <!-- 3. Даты оформления -->
        <td class="px-3 py-2 whitespace-nowrap">
            <span class="block"><span class="text-gray-400">Созд:</span> {{ $demand->dem_date ? \Carbon\Carbon::parse($demand->dem_date)->format('d.m.Y H:i') : '—' }}</span>
            <span class="block text-[10px] text-gray-500"><span class="text-gray-400">Изм:</span> {{ $demand->date_edit ? \Carbon\Carbon::parse($demand->date_edit)->format('d.m.Y H:i') : '—' }}</span>
            @if($demand->date_change_state)
                <span class="block text-[10px] text-orange-600"><span class="text-gray-400">Статус:</span> {{ \Carbon\Carbon::parse($demand->date_change_state)->format('d.m.Y') }}</span>
            @endif
        </td>
        
        <!-- 4. Пользователи / Сессия -->
        <td class="px-3 py-2">
            <span class="block font-medium text-gray-900"><span class="text-gray-400">Автор:</span> {{ $demand->creator->user_login ?? 'ID: '.$demand->id_user_create }}</span>
            <span class="block text-[10px] text-gray-500"><span class="text-gray-400">Редакт:</span> {{ $demand->editor->user_login ?? 'ID: '.$demand->id_user_edit }}</span>
            @if($demand->sessionid)
                <span class="block text-[9px] text-gray-400 bg-gray-100 rounded px-1 truncate max-w-[120px]" title="{{ $demand->sessionid }}">{{ $demand->sessionid }}</span>
            @endif
        </td>
        
        <!-- 5. Курс / УЗ -->
        <td class="px-3 py-2 max-w-[180px]">
            <span class="block font-medium text-gray-900 truncate" title="{{ $demand->course->course_name ?? '' }}">Курс: {{ $demand->course->course_name ?? 'ID: '.$demand->id_course }}</span>
            <span class="block text-[11px] text-gray-500 truncate" title="{{ $demand->vuz->vuz_name ?? '' }}">УЗ: {{ $demand->vuz->vuz_name ?? 'ID: '.$demand->id_vuz }}</span>
        </td>
        
        <!-- 6. Локация / Профессия -->
        <td class="px-3 py-2">
            <span class="block text-gray-800">{{ $demand->city->city_name ?? 'Город ID: '.$demand->id_city }} <small class="text-gray-400">({{ $demand->country->country_name ?? 'РФ' }})</small></span>
            <span class="block text-[10px] text-gray-500 truncate max-w-[140px]" title="{{ $demand->prof->prof_name ?? '' }}">Проф: {{ $demand->prof->prof_name ?? 'ID: '.$demand->id_prof }}</span>
        </td>
        
        <!-- 7. Период обучения -->
        <td class="px-3 py-2 whitespace-nowrap">
            <span class="block font-medium">с {{ $demand->date_start ? \Carbon\Carbon::parse($demand->date_start)->format('d.m.Y') : '...' }}</span>
            <span class="block font-medium">по {{ $demand->date_end ? \Carbon\Carbon::parse($demand->date_end)->format('d.m.Y') : '...' }}</span>
        </td>

        <!-- 8. Вид / Форма / Док -->
        <td class="px-3 py-2 max-w-[150px]">
            <span class="block text-gray-900 font-medium truncate">{{ $demand->edType->ed_type_name ?? 'Вид ID: '.$demand->idtypeeduc }}</span>
            <span class="block text-[10px] text-gray-500">{{ $demand->form->form_name ?? 'Форма ID: '.$demand->id_form }}</span>
            <span class="block text-[10px] text-indigo-600 truncate" title="{{ $demand->typeDoc->doc_type_name ?? '' }}">Док: {{ $demand->typeDoc->doc_type_name ?? 'ID: '.$demand->id_type_doc }}</span>
        </td>

        <!-- 9. Служебные / Флаги -->
        <td class="px-3 py-2 text-[10px]">
            <div class="flex flex-col gap-0.5">
                <span class="px-1.5 py-0.5 rounded text-center {{ $demand->isplanned ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-orange-700' }}">
                    {{ $demand->isplanned ? 'План' : 'Вне плана' }}
                </span>
                <span class="text-gray-400">Статус: <strong class="text-gray-700">{{ $demand->dem_status }}</strong></span>
                <span class="text-gray-400">Версия: <strong class="text-gray-700">{{ $demand->row_verion }}</strong></span>
                <span class="text-gray-400">Работник: <strong class="text-gray-700">{{ $demand->isworker }}</strong></span>
            </div>
        </td>
        
        <!-- 10. Действия (Кнопка просмотра абсолютно ВСЕХ полей) -->
        <td class="px-3 py-2 text-center align-middle">
            <a href="{{ route('requests.show', $demand->dem_id) }}" 
               class="inline-block text-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2 py-1 rounded transition-colors font-medium">
                Инфо
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="px-4 py-8 text-center text-sm text-gray-500">Заявки не найдены.</td>
    </tr>
@endforelse
