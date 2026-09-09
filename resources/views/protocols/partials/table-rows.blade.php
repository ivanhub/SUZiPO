@forelse($protocols as $protocol)
    <tr style="border-bottom: 1px solid #e5e7eb; height: 32px; background-color: #ffffff;" onmouseover="this.style.backgroundColor='#fffbeb'" onmouseout="this.style.backgroundColor='#ffffff'">
        
        <!-- 1. Действие (Иконка желтой папки) -->
        <td style="padding: 4px; border-right: 1px solid #e5e7eb; text-align: center; background-color: #f9fafb;">
            <button type="button" @click="openEdit({{ json_encode($protocol->load('demand.course')) }})" style="cursor: pointer; border: none; background: none; padding: 0; display: block; margin: 0 auto;">
                <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 15px; height: 15px; color: #f59e0b;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                </svg>
            </button>
        </td>

        <!-- 2. ID протокола -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #4b5563;">
            {{ $protocol->prot_id }}
        </td>
        
        <!-- 3. Состояние -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;">
            {{ $protocol->prot_status == 4 ? 'Принятые' : ($protocol->prot_status == 6 ? 'Архив' : '—') }}
        </td>
        
        <!-- 4. № Протокола -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->prot_num ?? '—' }}
        </td>

        <!-- 5. Создан (Дата) -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->format('d.m.Y') : '—' }}
        </td>
        
        <!-- 6. Название курса (Жесткое троеточие, текст гарантированно НЕ перенесется и НЕ раздует ячейку) -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; color: #111827;" title="{{ $protocol->demand?->course?->name ?? '' }}">
            {{ $protocol->demand?->course?->name ?? '—' }}
        </td>
        
        <!-- 7. Начало -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}
        </td>
        
        <!-- 8. Окончание -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}
        </td>
        
        <!-- 9. Редактор -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->editor?->user_name ?? '—' }}">
            {{ $protocol->editor?->user_name ?? '—' }}
        </td>
        
        <!-- 10. № Заявки -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->demand?->dem_num ?? '—' }}
        </td>
        
        <!-- 11. Дата Заявки -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->demand?->dem_date ? \Carbon\Carbon::parse($protocol->demand->dem_date)->format('d.m.Y') : '—' }}
        </td>

        <!-- 12. Квалификация -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->demand?->qualification ?? '' }}">
            {{ $protocol->demand?->qualification ?? '—' }}
        </td>

        <!-- 13. ФИО Преподавателя -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->demand?->teacher_name ?? '' }}">
            {{ $protocol->demand?->teacher_name ?? '—' }}
        </td>

        <!-- 14. Номер аудитории -->
        <td style="padding: 6px; border-right: 1px solid #e5e7eb; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ $protocol->demand?->classroom_number ?? '—' }}
        </td>

        <!-- 15. Куратор группы -->
        <td style="padding: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->demand?->curator_name ?? '' }}">
            {{ $protocol->demand?->curator_name ?? '—' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="15" style="padding: 16px; text-align: center; color: #9ca3af;">Протоколы не найдены.</td>
    </tr>
@endforelse
