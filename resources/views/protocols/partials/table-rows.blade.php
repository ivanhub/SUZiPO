@forelse($protocols as $protocol)
<tr>
    <!-- Состояние -->
    <td>
        @if($protocol->prot_status == 1)
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background-color: rgba(16, 185, 129, 0.14); color: #10B981;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #10B981;"></span>
                В работе
            </span>
        @elseif($protocol->prot_status == 0)
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background-color: rgba(239, 68, 68, 0.14); color: #EF4444;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #EF4444;"></span>
                Черновик
            </span>
        @elseif($protocol->prot_status == 4)
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background-color: var(--bg-surface); color: var(--text-secondary);">
                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: #6B7280;"></span>
                Завершен
            </span>
        @else
            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background-color: var(--bg-surface); color: var(--text-secondary);">
                Статус {{ $protocol->prot_status }}
            </span>
        @endif
    </td>

    <!-- № Рег. -->
    <td style="font-weight: 600; color: var(--text-primary);">{{ $protocol->prot_num }}</td>

    <!-- Создан -->
    <td style="color: var(--text-secondary);">
        {{ $protocol->prot_date ? \Carbon\Carbon::parse($protocol->prot_date)->format('d.m.Y') : '—' }}
    </td>

    <!-- Курс / Квалификация -->
    <td style="font-weight: 500; color: var(--text-primary); white-space: normal; overflow: hidden; text-overflow: ellipsis;" title="{{ $protocol->demand?->course?->course ?? '' }}">
        {{ $protocol->demand?->course?->course ?? '—' }}
        @if(isset($protocol->demand?->production_break))
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Разрыв: {{ $protocol->demand?->production_break }}</div>
        @endif
    </td>

    <!-- Начало обучения -->
    <td style="color: var(--text-primary);">
        {{ $protocol->date_start ? \Carbon\Carbon::parse($protocol->date_start)->format('d.m.Y') : '—' }}
    </td>

    <!-- Окончание обучения -->
    <td style="color: var(--text-primary);">
        {{ $protocol->date_end ? \Carbon\Carbon::parse($protocol->date_end)->format('d.m.Y') : '—' }}
    </td>

    <!-- Создатель / Редактор -->
    <td>
        <div style="font-weight: 500; color: var(--text-primary);">{{ $protocol->editor?->name ?? '—' }}</div>
        <div style="font-size: 11px; color: var(--text-secondary);">Ред: —</div>
    </td>

    <!-- № Заявки -->
    <td style="color: var(--text-secondary);">№ {{ $protocol->demand?->req_number ?? '—' }}</td>

    <!-- Дата Заявки -->
    <td style="color: var(--text-secondary);">
        {{ $protocol->demand?->created_at ? $protocol->demand?->created_at->format('d.m.Y') : '—' }}
    </td>

    <!-- Квалификация -->
    <td style="color: var(--text-secondary); white-space: normal; overflow: hidden; text-overflow: ellipsis;">
        {{ $protocol->demand?->profession?->profession ?? '—' }}
    </td>

    <!-- Преподаватель -->
    <td style="color: var(--text-primary); white-space: normal; overflow: hidden; text-overflow: ellipsis;">
        {{ $protocol->demand?->teacher?->fio ?? '—' }}
    </td>

    <!-- Аудитория -->
    <td style="text-align: center; color: var(--text-primary);">
        {{ $protocol->demand?->audience?->number ?? '—' }}
    </td>

    <!-- Куратор -->
    <td style="color: var(--text-primary); white-space: normal; overflow: hidden; text-overflow: ellipsis;">
        {{ $protocol->demand?->curator?->fio ?? '—' }}
    </td>

    <!-- Действия (Справа, как в Figma: Отчёт, Скачать, Редактировать) -->
    <td style="text-align: right;">
        <div style="display: inline-flex; align-items: center; gap: 4px;">
            <a href="{{ route('protocols.report.create', $protocol->prot_id) }}"
               target="_blank" title="Открыть отчёт" class="suzipo-action-circle">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </a>

            <a href="{{ route('protocols.export-yukos', $protocol->prot_id) }}"
               class="suzipo-action-circle"
               title="Выгрузить протокол в Excel">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </a>

            <button type="button" @click="openEdit({{ $protocol->prot_id }})"
                    title="Редактировать" class="suzipo-action-circle">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="14" style="padding: 28px; text-align: center; color: var(--text-secondary); font-size: 13px;">
        Протоколы не найдены
    </td>
</tr>
@endforelse
