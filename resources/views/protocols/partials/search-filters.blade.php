<td style="padding: 6px 8px;"><input type="text" x-model="searchStatus" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Статус..."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchProtNum" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="№ Рег."></td>
<td style="padding: 6px 8px; text-align: center; color: var(--text-secondary);">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchCourse" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Курс..."></td>
<td style="padding: 6px 8px; text-align: center; color: var(--text-secondary);">—</td>
<td style="padding: 6px 8px; text-align: center; color: var(--text-secondary);">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchEditor" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Автор..."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchDemNum" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="№ Заяв."></td>
<td style="padding: 6px 8px; text-align: center; color: var(--text-secondary);">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchQual" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Квал."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchTeacher" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Препод..."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchClassroom" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Ауд."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchCurator" @input.debounce.400ms="fetchData()" style="width: 100%; box-sizing: border-box;" placeholder="Куратор..."></td>
<td style="padding: 6px 8px; text-align: center; color: var(--text-secondary);">—</td>
