<?php

namespace App\Http\Controllers;

use App\Models\MatrixCourse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatrixCourseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, array(10, 25, 50, 100), true)) {
            $perPage = 10;
        }

        $matrixCourses = \App\Models\MatrixCourse::when($search, function ($query, $search) {
            $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
            $needle = mb_strtolower(trim($search), 'UTF-8');

            return $query->where(function ($q) use ($upper, $lower, $needle) {
                $q->whereRaw("TRANSLATE(CAST(program_name AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"])
                  ->orWhereRaw("TRANSLATE(CAST(program AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"])
                  ->orWhereRaw("TRANSLATE(CAST(code AS TEXT), '{$upper}', '{$lower}') LIKE ?", ["%{$needle}%"]);
            });
        })
        ->orderBy('number')
        ->paginate($perPage)
        ->withQueryString();

        return view('matrices.courses.index', compact('matrixCourses', 'search'));
    }


    public function create(): View
    {
        return view('matrices.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());

        try {
            MatrixCourse::create($validated);

            return redirect()->route('matrices.courses.index')
                ->with('success', 'Запись курса добавлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка создания курса: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании курса: ' . $e->getMessage());
        }
    }

    public function edit(MatrixCourse $course): View
    {
        return view('matrices.courses.edit', compact('course'));
    }

    public function update(Request $request, MatrixCourse $course): RedirectResponse
    {
        $validated = $request->validate($this->validationRules(), $this->validationMessages());

        try {
            $course->update($validated);

            return redirect()->route('matrices.courses.index')
                ->with('success', 'Запись курса обновлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка обновления курса #' . $course->id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при сохранении курса: ' . $e->getMessage());
        }
    }

    public function destroy(MatrixCourse $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('matrices.courses.index')
            ->with('success', 'Запись курса удалена успешно.');
    }

    protected function validationRules(): array
    {
        return [
            'program'                  => 'nullable|string|max:500',
            'number'                   => 'nullable|integer',
            'code'                     => 'nullable|string|max:50',
            'sro_education_type'       => 'nullable|string|max:255',
            'registry_number'          => 'nullable|string|max:100',
            'additional_code'          => 'nullable|string',
            'date_start'               => 'nullable|date',
            'date_end'                 => 'nullable|date',
            'education_type'           => 'nullable|string|max:500',
            'program_name'             => 'nullable|string',
            'full_name'                => 'nullable|string',
            'study_form'               => 'nullable|string|max:500',
            'hours'                    => 'nullable|integer',
            'theory_hours'             => 'nullable|integer',
            'self_study_hours'         => 'nullable|integer',
            'practical_hours'          => 'nullable|integer',
            'practice_hours'           => 'nullable|integer',
            'distance_practical_hours' => 'nullable|numeric',
            'cost_total'               => 'nullable|numeric',
            'cost_fulltime_theory'     => 'nullable|numeric',
            'cost_distance_theory'     => 'nullable|numeric',
            'cost_industrial_practice' => 'nullable|numeric',
            'cost_fulltime_practice'   => 'nullable|numeric',
            'cost_distance_practice'   => 'nullable|numeric',
            'listener_category'        => 'nullable|string|max:500',
            'group_size'               => 'nullable|string|max:100',
            'control_form'             => 'nullable|string|max:200',
            'commission_type'          => 'nullable|string|max:100',
            'document_type'            => 'nullable|string|max:200',
            'periodicity'              => 'nullable|string|max:255',
            'notes'                    => 'nullable|string',
            'uchipro'                  => 'nullable|string|max:200',
            'info_system'              => 'nullable|string|max:500',
            'teacher_requirements'     => 'nullable|string',
            'equipment'                => 'nullable|string',
            'equipment_location'       => 'nullable|string',
            'teacher_fio'              => 'nullable|string|max:500',
            'only_yung'                => 'nullable|string|max:100',
            'og'                       => 'nullable|string|max:100',
            'third_party'              => 'nullable|string|max:100',
            'site_publication'         => 'nullable|string|max:100',
            'inf_mo'                   => 'nullable|string|max:100',
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