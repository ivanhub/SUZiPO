@forelse($protocols as $protocol)
<tr class="hover:bg-gray-50" style="border-bottom: 1px solid #e5e7eb;">
    <!-- Действия (Иконки редактирования) -->
    <td style="padding: 10px 8px; text-align: center;">
        {{-- Передаем чистый ID без кавычек и текстов --}}
        <span @click="openEdit({{ $protocol->prot_id }})" style="cursor: pointer; color: #4f46e5; font-size: 14px; margin-right: 8px;" title="Редактировать">✏️</span>
        <a href="#" style="text-decoration: none; color: #10b981; font-size: 14px;" title="Скачать">📥</a>
    </td>


    <!-- ID Протокола -->
    <td style="padding: 10px 8px; font-weight: 500; color: #111827;">{{ $protocol->prot_id }}</td>

    <!-- Состояние (Текстовый статус на основе числового prot_status) -->
    <td style="padding: 10px 8px;">
        <span style="padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600;
            @if($protocol->prot_status == 1) background-color: #d1fae5; color: #065f46;
            @elseif($protocol->prot_status == 0) background-color: #fee2e2; color: #991b1b;
            @else background-color: #f3f4f6; color: #374151; @endif">
            @if($protocol->prot_status == 1) В работе
            @elseif($protocol->prot_status == 0) Черновик
            @elseif($protocol->prot_status == 4) Завершен
            @else Статус {{ $protocol->prot_status }} @endif
        </span>
    </td>

    <!-- № Рег. (Номер протокола, куда сел сквозной номер заявки 1-ЮЛ) -->
    <td style="padding: 10px 8px; font-weight: 600; color: #1f2937;">{{ $protocol->prot_num }}</td>

    <!-- Создан (Дата протокола) -->
    <td style="padding: 10px 8px; color: #6b7280;">
        {{ $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->format('d.m.Y') : '—' }}
    </td>

    <!-- Курс / Квалификация (Данные берутся из связанной заявки) -->
    <td style="padding: 10px 8px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->demand->course->course ?? '' }}">
        {{ $protocol->demand?->course->course ?? '—' }}
        @if(isset($protocol->demand?->production_break))
            <div style="font-size: 10px; color: #6b7280;">Разрыв: {{ $protocol->demand?->production_break }}</div>
        @endif
    </td>

    <!-- Начало обучения -->
    <td style="padding: 10px 8px; color: #374151;">
        {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}
    </td>

    <!-- Окончание обучения -->
    <td style="padding: 10px 8px; color: #374151;">
        {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}
    </td>

    <!-- Создатель / Редактор -->
    <td style="padding: 10px 8px; color: #4b5563;">
        <div style="font-weight: 500;">{{ $protocol->editor?->name ?? '—' }}</div>
        <div style="font-size: 10px; color: #9ca3af;">Ред: —</div>
    </td>

    <!-- № Заявки (Берем id исходной заявки из связи) -->
    <td style="padding: 10px 8px; color: #4b5563;">№ {{ $protocol->demand?->id ?? '—' }}</td>

    <!-- Дата Заявки -->
    <td style="padding: 10px 8px; color: #4b5563;">
        {{ $protocol->demand?->created_at ? $protocol->demand?->created_at->format('d.m.Y') : '—' }}
    </td>

    <!-- Квалификация -->
    <td style="padding: 10px 8px; color: #4b5563;">
        {{ $protocol->demand?->profession->profession ?? '—' }}
    </td>

    <!-- Преподаватель -->
    <td style="padding: 10px 8px; color: #4b5563; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        {{ $protocol->demand?->teacher->name ?? '—' }}
    </td>

    <!-- Аудитория -->
    <td style="padding: 10px 8px; text-align: center; color: #4b5563;">
        {{ $protocol->demand?->audience->name ?? '—' }}
    </td>

    <!-- Куратор -->
    <td style="padding: 10px 8px; color: #4b5563; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        {{ $protocol->demand?->curator->name ?? '—' }}
    </td>
</tr>
@empty
<tr>
    <td colspan="15" style="padding: 20px; text-align: center; color: #9ca3af; font-size: 13px;">
        Протоколы не найдены
    </td>
</tr>
@endforelse
