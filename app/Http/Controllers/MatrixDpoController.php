<?php

namespace App\Http\Controllers;

use App\Models\MatrixDpo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatrixDpoController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, array(10, 25, 50, 100), true)) {
            $perPage = 10;
        }

        $matrixDpos = \App\Models\MatrixDpo::when($search, function ($query, $search) {
            $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
            $needle = mb_strtolower(trim($search), 'UTF-8');

            return $query->where(function ($q) use ($upper, $lower, $needle) {
                $q->whereRaw("TRANSLATE(CAST(program_name AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"])
                  ->orWhereRaw("TRANSLATE(CAST(code AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"])
                  ->orWhereRaw("TRANSLATE(CAST(dpo_type AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"]);
            });
        })
        ->orderBy('row_number')
        ->paginate($perPage)
        ->withQueryString();

        return view('matrices.dpo.index', compact('matrixDpos', 'search'));
    }


    public function create(): View
    {
        return view('matrices.dpo.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());


        try {
            MatrixDpo::create($validated);

            return redirect()->route('matrices.dpo.index')
                ->with('success', 'Запись ДПО добавлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка создания записи ДПО: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании записи ДПО: ' . $e->getMessage());
        }
    }

    public function edit(MatrixDpo $matrixDpo): View
    {
        return view('matrices.dpo.edit', compact('matrixDpo'));
    }

    public function update(Request $request, MatrixDpo $matrixDpo): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());

        try {
            $matrixDpo->update($validated);

            return redirect()->route('matrices.dpo.index')
                ->with('success', 'Запись ДПО обновлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка обновления записи ДПО #' . $matrixDpo->id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при сохранении записи ДПО: ' . $e->getMessage());
        }
    }

    public function destroy(MatrixDpo $matrixDpo): RedirectResponse
    {
        $matrixDpo->delete();

        return redirect()->route('matrices.dpo.index')
            ->with('success', 'Запись ДПО удалена успешно.');
    }

    protected function validationRules(): array
    {
        return [
            'row_number'                => 'nullable|integer',
            'sro_education_type'        => 'nullable|string|max:255',
            'registry_number'           => 'nullable|string|max:100',
            'code'                      => 'nullable|string|max:50',
            'additional_code'           => 'nullable|string',
            'date_start'                => 'nullable|date',
            'date_end'                  => 'nullable|date',
            'dpo_type'                  => 'nullable|string',
            'program_name'              => 'nullable|string',
            'full_name'                 => 'nullable|string',
            'study_form'                => 'nullable|string',
            'total_hours'               => 'nullable|numeric',
            'theoretical_hours'         => 'nullable|numeric',
            'self_study_hours'          => 'nullable|numeric',
            'industrial_practice_hours' => 'nullable|numeric',
            'practical_hours'           => 'nullable|numeric',
            'distance_practical_hours'  => 'nullable|numeric',
            'cost_total'                => 'nullable|numeric',
            'cost_fulltime_theory'      => 'nullable|numeric',
            'cost_distance_theory'      => 'nullable|numeric',
            'cost_industrial_practice'  => 'nullable|numeric',
            'cost_fulltime_practice'    => 'nullable|numeric',
            'cost_distance_practice'    => 'nullable|numeric',
            'student_category'          => 'nullable|string',
            'group_capacity'            => 'nullable|string|max:50',
            'attestation_form'          => 'nullable|string|max:100',
            'commission_type'           => 'nullable|string',
            'issued_document'           => 'nullable|string|max:100',
            'periodicity'               => 'nullable|string|max:255',
            'note'                      => 'nullable|string',
            'uchi_pro'                  => 'nullable|string|max:100',
            'information_system_entry'  => 'nullable|string|max:100',
            'teacher_requirements'      => 'nullable|string',
            'equipment'                 => 'nullable|string',
            'equipment_location'        => 'nullable|string',
            'teacher_name'              => 'nullable|string',
            'only_yung'                 => 'nullable|string|max:100',
            'og'                        => 'nullable|string|max:100',
            'third_party'               => 'nullable|string|max:100',
            'site_publication'          => 'nullable|string|max:100',
            'inf_mo'                    => 'nullable|string|max:100',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            '*.integer' => 'Поле должно содержать только целое число (без букв и дробной части).',
            '*.numeric' => 'Поле должно содержать числовое значение (например: 1500.50).',
            '*.date'    => 'Укажите корректную дату в формате ДД.ММ.ГГГГ.',
            '*.max'     => 'Превышена максимальная длина текста (не более :max символов).',
            '*.string'  => 'Поле должно содержать текстовое значение.',
        ];
    }

}