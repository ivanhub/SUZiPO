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
        $validated = $request->validate([
            'row_number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'training_type' => 'nullable|string|max:100',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'total_hours' => 'nullable|integer',
            'fulltime_theoretical_hours' => 'nullable|numeric',
            'distance_theoretical_hours' => 'nullable|numeric',
            'industrial_practice_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|numeric',
            'student_category' => 'nullable|string',
            'group_capacity' => 'nullable|string|max:50',
            'control_form' => 'nullable|string|max:200',
            'commission_type' => 'nullable|string',
            'issued_document' => 'nullable|string|max:200',
            'note' => 'nullable|string',
            'uchi_pro' => 'nullable|string|max:100',
            'information_system_entry' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_name' => 'nullable|string',
            'code_ucjung' => 'nullable|string|max:100',
            'code_ul' => 'nullable|string|max:100',
        ]);

        MatrixOt::create($validated);

        return redirect()->route('matrices.ot.index')
            ->with('success', 'Запись ОТ добавлена успешно.');
    }

    public function edit(MatrixOt $matrixOt): View
    {
        return view('matrices.ot.edit', compact('matrixOt'));
    }

    public function update(Request $request, MatrixOt $matrixOt): RedirectResponse
    {
        $validated = $request->validate([
            'row_number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'training_type' => 'nullable|string|max:100',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'total_hours' => 'nullable|integer',
            'fulltime_theoretical_hours' => 'nullable|numeric',
            'distance_theoretical_hours' => 'nullable|numeric',
            'industrial_practice_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|numeric',
            'student_category' => 'nullable|string',
            'group_capacity' => 'nullable|string|max:50',
            'control_form' => 'nullable|string|max:200',
            'commission_type' => 'nullable|string',
            'issued_document' => 'nullable|string|max:200',
            'note' => 'nullable|string',
            'uchi_pro' => 'nullable|string|max:100',
            'information_system_entry' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_name' => 'nullable|string',
            'code_ucjung' => 'nullable|string|max:100',
            'code_ul' => 'nullable|string|max:100',
        ]);

        $matrixOt->update($validated);

        return redirect()->route('matrices.ot.index')
            ->with('success', 'Запись ОТ обновлена успешно.');
    }

    public function destroy(MatrixOt $matrixOt): RedirectResponse
    {
        $matrixOt->delete();

        return redirect()->route('matrices.ot.index')
            ->with('success', 'Запись ОТ удалена успешно.');
    }
}