<x-layouts.app-with-sidebar>
    <div class="max-w-full mx-auto p-2" x-data="{
    isEditOpen: false,
    editData: {},
    
    // Переменные для хранения текста из полей поиска
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

    openEdit(protocol) {
        this.editData = { ...protocol };
        this.isEditOpen = true;
    },

    // ВОТ ЭТОТ МЕТОД ОТВЕЧАЕТ ЗА ФИЛЬТРАЦИЮ:
    fetchData() {
    let params = new URLSearchParams({
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
        headers: { 
            'X-Requested-With': 'XMLHttpRequest' // Этот заголовок сообщает Laravel, что это AJAX
        }
    })
    .then(response => response.text())
    .then(html => {
        // Создаем временный виртуальный элемент для разбора пришедшего HTML
        let parser = new DOMParser();
        let doc = parser.parseFromString(html, 'text/html');
        let newBody = doc.getElementById('table-body');

        // Проверяем, нашли ли мы в ответе от сервера нужный кусок таблицы
        if (newBody) {
            document.getElementById('table-body').innerHTML = newBody.innerHTML;
        } else {
            // Если сервер прислал только строки (без обертки tbody), вставляем текст напрямую
            document.getElementById('table-body').innerHTML = html;
        }
    })
    .catch(error => console.error('Ошибка фильтрации:', error));
}}">


        <!-- Адаптивный контейнер: если экран маленький, появится горизонтальная прокрутка, но таблица не сожмется -->
        <!-- Контейнер скролла для мобильных экранов, на десктопе таблица займет 100% ширины экрана -->
        <div style="overflow-x: auto; width: 100%; border: 1px solid #d1d5db; border-radius: 4px; background: #ffffff; box-sizing: border-box;">

            <table style="table-layout: fixed; width: 100%; min-width: 1300px; border-collapse: collapse; font-family: sans-serif; font-size: 11px; color: #1f2937;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 1px solid #d1d5db; font-weight: bold; color: #4b5563;">
                        <th width="35" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: center;"></th>
                        <th width="65" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">ID</th>
                        <th width="85" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Состояние</th>
                        <th width="75" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">№ Прот.</th>
                        <th width="90" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Создан</th>

                        <th style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Курс / Программа обучения</th>

                        <th width="90" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Начало</th>
                        <th width="90" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Окончание</th>
                        <th width="130" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Редактор</th>
                        <th width="75" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">№ Заяв.</th>
                        <th width="90" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Дата Заяв.</th>
                        <th width="100" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Квалификация</th>
                        <th width="140" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Преподаватель</th>
                        <th width="85" style="padding: 6px; border-right: 1px solid #d1d5db; text-align: left;">Аудитория</th>
                        <th width="115" style="padding: 6px; text-align: left;">Куратор</th>
                    </tr>
                    <!-- Ряд фильтрации полей -->
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #d1d5db;">
                        <td style="padding: 4px; border-right: 1px solid #d1d5db; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchId" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="ID"></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchStatus" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Статус"></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchProtNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="№"></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchCourse" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Поиск курса..."></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db; text-align: center; color: #9ca3af;">—</td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchEditor" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="ФИО..."></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchDemNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="№ Заяв."></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db; text-align: center; color: #9ca3af;">—</td>
                        <!-- Фильтры для новых полей -->
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchQual" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Квал."></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchTeacher" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Препод."></td>
                        <td style="padding: 4px; border-right: 1px solid #d1d5db;"><input type="text" x-model="searchClassroom" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Ауд."></td>
                        <td style="padding: 4px;"><input type="text" x-model="searchCurator" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 2px; border: 1px solid #ccc; border-radius: 3px; box-sizing: border-box;" placeholder="Куратор"></td>
                    </tr>
                </thead>
                <tbody id="table-body" style="background-color: #ffffff;">
                    @include('protocols.partials.table-rows')
                </tbody>
            </table>
        </div>
        <!-- Блок пагинации: выводит кнопки "Вперед" и "Назад" -->
        <!-- Блок кастомной пагинации без английского текста -->
        <div id="pagination-container" style="margin-top: 15px; display: flex; justify-content: flex-end; align-items: center; gap: 8px; font-family: Arial, sans-serif; font-size: 12px;">

            <!-- Кнопка "Назад" -->
            @if ($protocols->onFirstPage())
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&larr;</span>
            @else
            <a href="{{ $protocols->previousPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">&larr;</a>
            @endif

            <!-- Текущее состояние страницы (опционально, для информативности) -->
            <span style="color: #4b5563; padding: 0 4px;">Страница {{ $protocols->currentPage() }}</span>

            <!-- Кнопка "Вперед" -->
            @if ($protocols->hasMorePages())
            <a href="{{ $protocols->nextPageUrl() }}" style="padding: 5px 10px; border: 1px solid #a5a5a5; color: #111827; background-color: #ffffff; text-decoration: none; border-radius: 3px; font-weight: bold;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">&rarr;</a>
            @else
            <span style="padding: 5px 10px; border: 1px solid #d1d5db; color: #9ca3af; background-color: #f3f4f6; cursor: not-allowed; border-radius: 3px; font-weight: bold;">&rarr;</span>
            @endif

        </div>
        @include('protocols.partials.edit-modal')
    </div>
</x-layouts.app-with-sidebar>