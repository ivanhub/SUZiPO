<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-6" style="font-family: system-ui, -apple-system, sans-serif; background-color: #fafafa; min-height: 100vh;" x-data="{
    isEditOpen: false,
    editData: {},
    activeTab: 'all',
    searchId: '',
    searchStatus: '',
    searchProtNum: '',
    searchCourse: '',
    searchEditor: '',
    searchDemNum: '',
    searchQual: '',
    searchTeacher: '',
    searchClassroom: '',
    searchCurator: '',

        openEdit(id) {
        fetch(`/protocols/${id}/json`)
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при загрузке протокола');
                return response.json();
            })
            .then(data => {
                this.editData = data;
                this.isEditOpen = true;
            })
            .catch(error => {
                console.error('Ошибка AJAX:', error);
                alert('Не удалось загрузить данные протокола.');
            });
    },


    switchTab(tabId) {
        this.activeTab = tabId;
        this.fetchData();
    },

    fetchData() {
        let params = new URLSearchParams({
            activeTab: this.activeTab,
            searchId: this.searchId || '',
            searchStatus: this.searchStatus || '',
            searchProtNum: this.searchProtNum || '',
            searchCourse: this.searchCourse || '',
            searchEditor: this.searchEditor || '',
            searchDemNum: this.searchDemNum || '',
            searchQual: this.searchQual || '',
            searchTeacher: this.searchTeacher || '',
            searchClassroom: this.searchClassroom || '',
            searchCurator: this.searchCurator || ''
        });

        fetch(`${window.location.pathname}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newBody = doc.getElementById('table-body');
            if (newBody) {
                document.getElementById('table-body').innerHTML = newBody.innerHTML;
            } else {
                document.getElementById('table-body').innerHTML = html;
            }
        })
        .catch(error => console.error('Ошибка фильтрации:', error));
    }
}">

        <!-- Верхний заголовок системы как на скрине -->
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">Реестр протоколов обучения</h1>
            <p style="font-size: 13px; color: #6b7280; margin: 0;">Система управления заявками и протоколами обучения (СУЗиПО)</p>
        </div>

        <!-- Горизонтальные табы-переключатели со счетчиками -->
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; font-size: 13px; border-bottom: 1px solid #e5e7eb; padding-bottom: 12px; overflow-x: auto; white-space: nowrap;">
            <span @click="switchTab('all')"
                :style="activeTab === 'all' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Все протоколы
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $totalCount }}</b>
            </span>

            <span @click="switchTab('1')"
                :style="activeTab === '1' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Активные
                <b style="background: #d1fae5; color: #065f46; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[1] ?? 0 }}</b>
            </span>

            <span @click="switchTab('0')"
                :style="activeTab === '0' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Черновики
                <b style="background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[0] ?? 0 }}</b>
            </span>

            <span @click="switchTab('4')"
                :style="activeTab === '4' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Завершенные
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[4] ?? 0 }}</b>
            </span>

            <span @click="switchTab('2')"
                :style="activeTab === '2' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Просроченные
                <b style="background: #e5e7eb; color: #1f2937; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[2] ?? 0 }}</b>
            </span>

            <span @click="switchTab('3')"
                :style="activeTab === '3' ? 'color: #1f2937; font-weight: 600; border-bottom: 2px solid #3b82f6; padding-bottom: 14px; margin-bottom: -14px; cursor: pointer;' : 'color: #6b7280; cursor: pointer; padding-bottom: 14px;'"
                style="display: flex; align-items: center; gap: 6px;">
                Отмененные
                <b style="background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-size: 11px;">{{ $counts[3] ?? 0 }}</b>
            </span>
        </div>


        <!-- Табличный блок -->
        <!-- Табличный блок -->
        <div style="overflow-x: auto; width: 100%; border: 1px solid #e5e7eb; border-radius: 8px; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); box-sizing: border-box;">
            <table style="table-layout: fixed; width: 100%; min-width: 1450px; border-collapse: collapse; font-size: 12px; color: #374151;">
                <thead>
                    <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb; color: #7c8594; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em;">
                        <th width="75" style="padding: 12px 8px; text-align: center;"></th>
                        <th width="65" style="padding: 12px 8px; text-align: left;">ID</th>
                        <th width="100" style="padding: 12px 8px; text-align: left;">Состояние</th>
                        <th width="85" style="padding: 12px 8px; text-align: left;">№ Рег.</th>
                        <th width="105" style="padding: 12px 8px; text-align: left;">Создан</th>
                        <th style="padding: 12px 8px; text-align: left;">Курс / Квалификация</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Начало</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Окончание</th>
                        <th width="160" style="padding: 12px 8px; text-align: left;">Создатель / Редактор</th>
                        <th width="85" style="padding: 12px 8px; text-align: left;">№ Заяв.</th>
                        <th width="105" style="padding: 12px 8px; text-align: left;">Дата Заяв.</th>
                        <th width="110" style="padding: 12px 8px; text-align: left;">Квалификация</th>
                        <th width="150" style="padding: 12px 8px; text-align: left;">Преподаватель</th>
                        <th width="85" style="padding: 12px 8px; text-align: left;">Ауд.</th>
                        <th width="130" style="padding: 12px 8px; text-align: left;">Куратор</th>
                    </tr>
                    <tr style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 6px 8px; text-align: center; color: #9ca3af;"></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchId" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="ID"></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchStatus" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="—"></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchProtNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="Поиск №"></td>
                        <td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchCourse" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="Название..."></td>
                        <td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchEditor" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="ФИО..."></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchDemNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="№ Заяв."></td>
                        <td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchQual" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="Квал."></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchTeacher" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="Препод..."></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchClassroom" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="№"></td>
                        <td style="padding: 6px 8px;"><input type="text" x-model="searchCurator" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 12px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; background:#f9fafb;" placeholder="Куратор"></td>
                    </tr>
                </thead>
                <tbody id="table-body" style="background-color: #ffffff;">
                    @include('protocols.partials.table-rows')
                </tbody>
            </table>
        </div>

                <!-- Блок пагинации -->
        <div id="pagination-container" style="margin-top: 15px; display: flex; justify-content: flex-end; align-items: center; gap: 8px; font-family: Arial, sans-serif; font-size: 12px;">
            @if ($protocols->onFirstPage())
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&larr;</span>
            @else
            <a href="{{ $protocols->previousPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;">&larr;</a>
            @endif

            <span style="color: #4b5563; padding: 0 4px;">Страница {{ $protocols->currentPage() }}</span>

            @if ($protocols->hasMorePages())
            <a href="{{ $protocols->nextPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;">&rarr;</a>
            @else
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&rarr;</span>
            @endif
        </div>
        
        <!-- Подключение нашего Pop-up окна редактирования -->
        @include('protocols.partials.edit-modal')
    </div>
</x-layouts.app-with-sidebar>
