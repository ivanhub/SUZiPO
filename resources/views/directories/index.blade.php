<x-layouts.app-with-sidebar>
    <div style="padding: 8px 0 32px 0;" x-data="{ search: '' }">

        <!-- Заголовок и строка живого поиска -->
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin: 0;">Справочники системы</h1>
                <p style="font-size: 13px; color: var(--text-secondary); margin: 4px 0 0 0;">Выберите нужный классификатор или реестр для просмотра и редактирования</p>
            </div>

            <!-- Поле мгновенного поиска -->
            <div style="position: relative; width: 320px; max-width: 100%;">
                <input type="text" x-model="search" placeholder="Быстрый поиск справочника..."
                       class="suzipo-field" style="padding-left: 36px !important;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                     style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
                </svg>
            </div>
        </div>

        <!-- Группы карточек справочников -->
        @foreach($groups as $group)
        <div style="margin-bottom: 28px;"
             x-show="search === '' || Array.from($el.querySelectorAll('[data-dir-card]')).some(el => el.dataset.search.includes(search.toLowerCase()))">
            <div class="suzipo-section-title" style="margin-top: 0;">{{ $group['title'] }}</div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 14px;">
                @foreach($group['items'] as $dir)
                @php
                    $url = \Illuminate\Support\Facades\Route::has($dir['route']) ? route($dir['route']) : '#';
                    $searchStr = mb_strtolower($dir['name'] . ' ' . $dir['desc'], 'UTF-8');
                @endphp
                <a href="{{ $url }}"
                   data-dir-card
                   data-search="{{ $searchStr }}"
                   x-show="search === '' || $el.dataset.search.includes(search.toLowerCase())"
                   class="suzipo-card"
                   style="display: flex; flex-direction: column; justify-content: space-between; padding: 18px 20px; text-decoration: none; border: 1px solid var(--border-color); transition: transform 0.12s ease, border-color 0.15s ease;"
                   onmouseover="this.style.borderColor='#FFD100'"
                   onmouseout="this.style.borderColor='var(--border-color)'">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 6px;">
                            <span style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $dir['name'] }}</span>
                            @if(!is_null($dir['count']))
                                <span style="font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 9999px; background-color: var(--bg-page); color: var(--text-secondary); border: 1px solid var(--border-color); flex-shrink: 0;">
                                    {{ number_format($dir['count'], 0, '', ' ') }}
                                </span>
                            @endif
                        </div>
                        <p style="font-size: 12px; color: var(--text-secondary); margin: 0; line-height: 1.4;">{{ $dir['desc'] }}</p>
                    </div>

                    <div style="margin-top: 14px; display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #D97706;">
                        Открыть справочник <span>→</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</x-layouts.app-with-sidebar>
