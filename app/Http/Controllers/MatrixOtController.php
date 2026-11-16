<?php

namespace App\Http\Controllers;

use App\Models\MatrixOt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatrixOtController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $matrixOts = MatrixOt::when($search, function ($query, $search) {
            return $query->where('program_name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('training_type', 'like', "%{$search}%");
        })
        ->orderBy('row_number')
        ->paginate(20);

        return view('matrices.ot.index', compact('matrixOts', 'search'));
    }

    public function create(): View
    {
        return view('matrices.ot.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        try {
            MatrixOt::create($validated);

            return redirect()->route('matrices.ot.index')
                ->with('success', 'Запись ОТ добавлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка создания записи ОТ: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании записи ОТ: ' . $e->getMessage());
        }
    }

    public function edit(MatrixOt $matrixOt): View
    {
        return view('matrices.ot.edit', compact('matrixOt'));
    }

    public function update(Request $request, MatrixOt $matrixOt): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        try {
            $matrixOt->update($validated);

            return redirect()->route('matrices.ot.index')
                ->with('success', 'Запись ОТ обновлена успешно.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка обновления записи ОТ #' . $matrixOt->id . ': ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при сохранении записи ОТ: ' . $e->getMessage());
        }
    }

    public function destroy(MatrixOt $matrixOt): RedirectResponse
    {
        $matrixOt->delete();

        return redirect()->route('matrices.ot.index')
            ->with('success', 'Запись ОТ удалена успешно.');
    }

    protected function validationRules(): array
    {
        return [
            'row_number'                 => 'nullable|integer',
            'sro_education_type'         => 'nullable|string|max:255',
            'registry_number'            => 'nullable|string|max:100',
            'code'                       => 'nullable|string|max:50',
            'additional_code'            => 'nullable|string',
            'date_start'                 => 'nullable|date',
            'date_end'                   => 'nullable|date',
            'training_type'              => 'nullable|string|max:100',
            'program_name'               => 'nullable|string',
            'full_name'                  => 'nullable|string',
            'study_form'                 => 'nullable|string',
            'total_hours'                => 'nullable|numeric',
            'fulltime_theoretical_hours' => 'nullable|numeric',
            'distance_theoretical_hours' => 'nullable|numeric',
            'industrial_practice_hours'  => 'nullable|numeric',
            'practical_hours'            => 'nullable|numeric',
            'distance_practical_hours'   => 'nullable|numeric',
            'cost_total'                 => 'nullable|numeric',
            'cost_fulltime_theory'       => 'nullable|numeric',
            'cost_distance_theory'       => 'nullable|numeric',
            'cost_industrial_practice'   => 'nullable|numeric',
            'cost_fulltime_practice'     => 'nullable|numeric',
            'cost_distance_practice'     => 'nullable|numeric',
            'student_category'           => 'nullable|string',
            'group_capacity'             => 'nullable|string|max:50',
            'control_form'               => 'nullable|string|max:200',
            'commission_type'            => 'nullable|string',
            'issued_document'            => 'nullable|string|max:200',
            'periodicity'                => 'nullable|string|max:255',
            'note'                       => 'nullable|string',
            'uchi_pro'                   => 'nullable|string|max:100',
            'information_system_entry'   => 'nullable|string',
            'teacher_requirements'       => 'nullable|string',
            'equipment'                  => 'nullable|string',
            'equipment_location'         => 'nullable|string',
            'teacher_name'               => 'nullable|string',
            'only_yung'                  => 'nullable|string|max:100',
            'og'                         => 'nullable|string|max:100',
            'third_party'                => 'nullable|string|max:100',
            'site_publication'           => 'nullable|string|max:100',
            'inf_mo'                     => 'nullable|string|max:100',
            'code_ucjung'                => 'nullable|string|max:100',
            'code_ul'                    => 'nullable|string|max:100',
        ];
    }
}