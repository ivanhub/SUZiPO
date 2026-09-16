<div x-show="isEditOpen"
    style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.4); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;"
    x-cloak>

    <!-- Белая карточка поп-апа с фиксированной шириной и внутренним скроллом -->
    <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 950px; max-height: 90vh; overflow-y: auto; display: flex; flex-direction: column; font-family: system-ui, -apple-system, sans-serif; font-size: 13px; color: #1f2937; box-sizing: border-box;">

        <!-- Заголовок окна -->
        <div style="padding: 14px 20px; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; box-sizing: border-box;">
            <span style="font-weight: 700; font-size: 15px; color: #111827;">Редактирование записи протокола</span>
            <button type="button" @click="isEditOpen = false" style="margin-left: auto; border: none; background: none; font-size: 22px; color: #9ca3af; cursor: pointer; line-height: 1; padding: 0;" onmouseover="this.style.color='#4b5563'" onmouseout="this.style.color='#9ca3af'">&times;</button>
        </div>

        <!-- Тело формы -->
        <form :action="`/protocols/${editData.prot_id}`" method="POST" style="padding: 24px; margin: 0; box-sizing: border-box;">
            @csrf
            @method('PUT')

            <!-- Основной двухколоночный контейнер, разделяющий Заявку и Реквизиты -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start;">

                <!-- ЛЕВАЯ КОЛОНКА: Данные из заявки -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="text-align: right; font-weight: 600; color: #4b5563; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 4px;">Данные из заявки</div>

                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <input type="checkbox" disabled id="is_disposable" :checked="editData.demand?.one_time == 1" style="margin: 0; cursor: not-allowed; width: 16px; height: 16px; border-radius: 4px; border-color: #d1d5db;">
                        <label for="is_disposable" style="color: #dc2626; font-weight: 600;">Одноразовая заявка</label>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Дата начала курса:</label>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.start_date ? new Date(editData.demand.start_date).toLocaleDateString('ru-RU') : '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Дата окончания курса:</label>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.end_date ? new Date(editData.demand.end_date).toLocaleDateString('ru-RU') : '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Форма образования:</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.education_form || 'дистанционное'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Учебное заведение:</span>
                        <span style="font-weight: 600; color: #111827;">Учебный центр ООО «РН-Юганскнефтегаз»</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Наименование курса:</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.course?.course || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Место проведения (страна):</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.country || 'РОССИЯ'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Место проведения (город):</span>
                        <span style="font-weight: 600; color: #111827;">Нефтеюганск</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Аудитории:</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.audience?.name || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">ФИО преподавателей:</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.teacher?.name || '—'"></span>
                    </div>

                    <div style="display: grid; grid-template-columns: 180px 1fr; gap: 8px; padding: 4px 0;">
                        <span style="color: #6b7280;">Кураторы группы:</span>
                        <span style="font-weight: 600; color: #111827;" x-text="editData.demand?.curator?.name || '—'"></span>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 6px; margin-top: 8px;">
                        <span style="color: #4b5563; font-weight: 500;">Профессия, присваиваемая по результатам обучения:</span>
                        <select name="assigned_profession_id" disabled style="width: 100%; padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #f3f4f6; cursor: not-allowed;">
                            <option value="" x-text="editData.demand?.profession?.profession || 'Не указана'"></option>
                        </select>
                    </div>
                </div>

                <!-- ПРАВАЯ КОЛОНКА: Реквизиты протокола -->
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="text-align: right; font-weight: 600; color: #4b5563; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-bottom: 4px;">Реквизиты протокола</div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Номер протокола: <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="prot_num" x-model="editData.prot_num" readonly style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #f3f4f6; cursor: not-allowed;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Дата формирования:</label>
                        <input type="date" name="prot_date" x-model="editData.prot_date" style="width: 160px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Номер группы: <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="group_num" x-model="editData.group_num" required style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Номер приказа: <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="order_num" x-model="editData.order_num" style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>

                                        <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Дата назначения приказа:</label>
                        <input type="date" name="order_date" x-model="editData.order_date" style="width: 160px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Цена за человека (без НДС): <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="price_per_man" x-model="editData.price_per_man" style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-align: right;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">НДС%:</label>
                        <input type="text" name="nds_percent" x-model="editData.nds_percent" style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-align: right;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">№/дата договора:</label>
                        <select name="contract_id" style="width: 100%; max-width: 220px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #ffffff;">
                            <option value="">—</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Часы теории: <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="theory_hours" x-model="editData.theory_hours" style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-align: right;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Часы практики: <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="practice_hours" x-model="editData.practice_hours" style="width: 120px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-align: right;">
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Часы по программе: <span style="color: #dc2626;">*</span></label>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <input type="number" name="program_hours" readonly style="width: 120px; padding: 5px 8px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 13px; background-color: #f3f4f6; text-align: right;" :value="Number(editData.theory_hours || 0) + Number(editData.practice_hours || 0)">
                            <button type="button" style="padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; background: #f3f4f6; cursor: pointer; font-size: 12px;">📊</button>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Тип документа:</label>
                        <select name="document_type" x-model="editData.document_type" style="width: 160px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #ffffff;">
                            <option value="Сертификат">Сертификат</option>
                            <option value="Удостоверение">Удостоверение</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 220px 1fr; align-items: center; gap: 8px;">
                        <label style="color: #4b5563; font-weight: 500;">Номер распоряжения: <span style="color: #dc2626;">*</span></label>
                        <select name="disposition_id" style="width: 100%; max-width: 220px; padding: 5px 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #ffffff;">
                            <option value="" x-text="editData.order_num ? `(${editData.order_date || ''}) ${editData.order_num}` : '—'"></option>
                        </select>
                    </div>
                    
                    <input type="hidden" name="flagapproved" :value="editData.prot_status || 1">
                </div> <!-- Конец правой колонки -->
            </div> <!-- Конец двухколоночного контейнера -->

            <div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" @click="isEditOpen = false" style="padding: 7px 16px; border: 1px solid #d1d5db; border-radius: 6px; background: #ffffff; font-weight: 500; cursor: pointer;">Отмена</button>
                <button type="submit" style="padding: 7px 16px; border: none; border-radius: 6px; background: #3b82f6; color: #ffffff; font-weight: 500; cursor: pointer;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>
