<td style="padding: 6px 8px; text-align: center; color: #9ca3af;"></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchId" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="ID"></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchStatus" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="—"></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchProtNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="№ Рег."></td>
<td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchCourse" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Курс..."></td>
<td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
<td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchEditor" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Автор..."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchDemNum" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="№ Заяв."></td>
<td style="padding: 6px 8px; text-align: center; color: #9ca3af;">—</td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchQual" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Квал."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchTeacher" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Препод..."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchClassroom" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Ауд."></td>
<td style="padding: 6px 8px;"><input type="text" x-model="searchCurator" @input.debounce.400ms="fetchData()" style="width: 100%; font-size: 11px; padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box;" placeholder="Куратор..."></td>
