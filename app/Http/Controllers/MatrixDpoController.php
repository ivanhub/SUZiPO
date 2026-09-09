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
        
        $matrixDpos = MatrixDpo::when($search, function ($query, $search) {
            return $query->where('program_name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('dpo_type', 'like', "%{$search}%");
        })
        ->orderBy('row_number')
        ->paginate(20);

        return view('matrices.dpo.index', compact('matrixDpos', 'search'));
    }

    public function create(): View
    {
        return view('matrices.dpo.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'row_number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'dpo_type' => 'nullable|string',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'total_hours' => 'nullable|integer',
            'theoretical_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'industrial_practice_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'student_category' => 'nullable|string',
            'group_capacity' => 'nullable|string|max:50',
            'attestation_form' => 'nullable|string|max:100',
            'commission_type' => 'nullable|string',
            'issued_document' => 'nullable|string|max:100',
            'note' => 'nullable|string',
            'uchi_pro' => 'nullable|string|max:100',
            'information_system_entry' => 'nullable|string|max:100',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_name' => 'nullable|string',
        ]);

        MatrixDpo::create($validated);

        return redirect()->route('matrices.dpo.index')
            ->with('success', 'Запись ДПО добавлена успешно.');
    }

    public function edit(MatrixDpo $matrixDpo): View
    {
        return view('matrices.dpo.edit', compact('matrixDpo'));
    }

    public function update(Request $request, MatrixDpo $matrixDpo): RedirectResponse
    {
        $validated = $request->validate([
            'row_number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'dpo_type' => 'nullable|string',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'total_hours' => 'nullable|integer',
            'theoretical_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'industrial_practice_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'student_category' => 'nullable|string',
            'group_capacity' => 'nullable|string|max:50',
            'attestation_form' => 'nullable|string|max:100',
            'commission_type' => 'nullable|string',
            'issued_document' => 'nullable|string|max:100',
            'note' => 'nullable|string',
            'uchi_pro' => 'nullable|string|max:100',
            'information_system_entry' => 'nullable|string|max:100',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_name' => 'nullable|string',
        ]);

        $matrixDpo->update($validated);

        return redirect()->route('matrices.dpo.index')
            ->with('success', 'Запись ДПО обновлена успешно.');
    }

    public function destroy(MatrixDpo $matrixDpo): RedirectResponse
    {
        $matrixDpo->delete();

        return redirect()->route('matrices.dpo.index')
            ->with('success', 'Запись ДПО удалена успешно.');
    }
}