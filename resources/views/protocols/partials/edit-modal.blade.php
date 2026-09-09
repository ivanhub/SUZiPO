<!-- Главный оверлей: фиксируется на весь экран, затемняет фон и выравнивает поп-ап ровно по центру -->
<div x-show="isEditOpen"
    style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.4); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;"
    x-cloak>

    <!-- Белая карточка поп-апа с фиксированной шириной и внутренним скроллом -->
    <div style="background-color: #ffffff; border: 1px solid #c5c5c5; border-radius: 4px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); width: 100%; max-width: 950px; max-height: 92vh; overflow-y: auto; display: flex; flex-direction: column; font-family: Arial, sans-serif; font-size: 11px; color: #000000; box-sizing: border-box;">

        <!-- Заголовок окна -->
        <div style="padding: 10px 16px; background-color: #f3f4f6; border-bottom: 1px solid #d1d5db; display: flex; align-items: center; box-sizing: border-box;">
            <span style="font-weight: bold; font-size: 12px; color: #1f2937;">Редактирование записи протокола</span>
            <button type="button" @click="isEditOpen = false" style="margin-left: auto; border: none; background: none; font-size: 18px; color: #9ca3af; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
        </div>

        <!-- Тело формы -->
        <form :action="`/protocols/${editData.prot_id}`" method="POST" style="padding: 20px; margin: 0; box-sizing: border-box;">
            @csrf
            @method('PUT')

            <!-- Основной двухколоночный контейнер, разделяющий Заявку и Реквизиты -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">

                <!-- ЛЕВАЯ КОЛОНКА: Данные из заявки -->
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="text-align: right; font-weight: bold; color: #7c8594; font-size: 11px; border-bottom: 1px solid #d1d5db; padding-bottom: 3px; margin-bottom: 8px;">Данные из заявки</div>
                    
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                        <input type="checkbox" name="is_disposable" id="is_disposable" :checked="editData.demand?.is_disposable == 1" style="margin: 0; cursor: pointer;">
                        <label for="is_disposable" style="color: #ef4444; font-weight: bold;">Одноразовая заявка</label>
                    </div>

                    <!-- ИСПРАВЛЕНО: Привязка дат к полям из app_protocols -->
                    <div style="display: grid; grid-template-columns: 160px 1fr; align-items: center; gap: 4px;">
                        <label>Дата начала курса: <span style="color: #ef4444;">*</span></label>
                        <input type="date" name="date_start" x-model="editData.date_start" style="width: 140px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; align-items: center; gap: 4px;">
                        <label>Дата окончания курса:</label>
                        <input type="date" name="date_end" x-model="editData.date_end" style="width: 140px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <!-- ИСПРАВЛЕНО: Приведение названий свойств к реальному snake_case из вашей СУБД -->
                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Форма образования:</span>
                        <span style="font-weight: bold;" x-text="editData.demand?.form_education || 'дистанционное'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Учебное заведение:</span>
                        <span style="font-weight: bold;" x-text="editData.demand?.educational_institution || 'Учебный центр ООО «РН-Юганскнефтегаз»'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Наименование курса (тематика):</span>
                        <span style="font-weight: bold; color: #111827;" x-text="editData.demand?.course?.name || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Место проведения (страна):</span>
                        <span style="font-weight: bold;">РОССИЯ</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Место проведения (город):</span>
                        <span style="font-weight: bold;">Нефтеюганск</span>
                    </div>

                    <!-- ИСПРАВЛЕНО: Вывод динамических данных из связи с app_demands -->
                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Аудитории:</span>
                        <span style="font-weight: bold;" x-text="editData.demand?.classroom_number || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">ФИО преподавателей:</span>
                        <span style="font-weight: bold;" x-text="editData.demand?.teacher_name || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 160px 1fr; gap: 4px;">
                        <span style="color: #555555;">Кураторы группы:</span>
                        <span style="font-weight: bold;" x-text="editData.demand?.curator_name || '—'"></span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px; margin-top: 6px;">
                        <span style="color: #555555; font-weight: 500;">Профессия, присваиваемая по результатам обучения:</span>
                        <select name="assigned_profession_id" style="width: 100%; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; background-color: #f9fafb;">
                            <option value="" x-text="editData.demand?.course?.name || 'Выберите профессию'"></option>
                        </select>
                    </div>
                </div>

                <!-- ПРАВАЯ КОЛОНКА: Реквизиты протокола -->
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div style="text-align: right; font-weight: bold; color: #7c8594; font-size: 11px; border-bottom: 1px solid #d1d5db; padding-bottom: 3px; margin-bottom: 8px;">Реквизиты протокола</div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Номер протокола: <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="prot_num" x-model="editData.prot_num" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Дата формирования протокола:</label>
                        <input type="date" name="prot_date" x-model="editData.prot_date" style="width: 140px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Номер группы: <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="group_num" x-model="editData.group_num" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Номер приказа: <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="order_num" x-model="editData.order_num" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Дата назначения приказа:</label>
                        <input type="date" name="order_date" x-model="editData.order_date" style="width: 140px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Цена за человека (без НДС): <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="cost" x-model="editData.Cost" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; text-align: right;" value="0,00">
                    </div>

                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>НДС%:</label>
                        <input type="text" name="cost_vat" x-model="editData.cost_vat" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; text-align: right;" value="0">
                    </div>

                    <!-- Номер/дата договора -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>№/дата договора на обучение/оценку: <span style="color: #ef4444;">*</span></label>
                        <select name="contract_id" style="width: 180px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; background-color: #ffffff;">
                            <option value="">—</option>
                        </select>
                    </div>

                    <!-- Часы теории -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Количество часов теоретического обучения: <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="teor_count_hours" x-model="editData.teorcounthours" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; text-align: right;">
                    </div>

                    <!-- Часы практики -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Количество часов производственно-практического обучения: <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="prac_count_hours" x-model="editData.praccounthours" style="width: 100px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; text-align: right;">
                    </div>

                    <!-- Часы по программе -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Количество часов по программе: <span style="color: #ef4444;">*</span></label>
                        <div style="display: flex; align-items: center; gap: 4px;">
                            <input type="text" readonly style="width: 100px; padding: 2px; border: 1px solid #ccc; font-size: 11px; background-color: #f3f4f6; text-align: right;" :value="Number(editData.teor_count_hours || 0) + Number(editData.prac_count_hours || 0)">
                            <button type="button" style="padding: 2px 4px; border: 1px solid #a5a5a5; background: #f3f4f6; cursor: pointer; font-size: 10px;">📊</button>
                        </div>
                    </div>

                    <!-- Тип документа -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>Тип документа:</label>
                        <select name="typedoc_id" x-model="editData.typedoc_id" style="width: 140px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; background-color: #ffffff;">
                            <option value="1">Сертификат</option>
                            <option value="2">Удостоверение</option>
                        </select>
                    </div>

                    <!-- Номер распоряжения -->
                    <div style="display: grid; grid-template-columns: 200px 1fr; align-items: center; gap: 4px;">
                        <label>(Дата)Номер распоряжения: <span style="color: #ef4444;">*</span></label>
                        <select name="disposition_id" style="width: 180px; padding: 2px; border: 1px solid #a5a5a5; font-size: 11px; background-color: #ffffff;">
                            <option value="" x-text="editData.order_num ? `(${editData.order_date || ''}) ${editData.order_num}` : '—'"></option>
                        </select>
                    </div>

                </div> <!-- Конец правой колонки -->
            </div> <!-- Конец двухколоночного контейнера (Заявка / Реквизиты) -->