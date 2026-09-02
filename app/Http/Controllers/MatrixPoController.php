<?php

namespace App\Http\Controllers;

use App\Models\MatrixPo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatrixPoController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $matrixPos = MatrixPo::when($search, function ($query, $search) {
            return $query->where('profession_name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('education_type', 'like', "%{$search}%");
        })
        ->orderBy('number')
        ->paginate(20);

        return view('matrices.po.index', compact('matrixPos', 'search'));
    }

    public function create(): View
    {
        return view('matrices.po.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'education_type' => 'nullable|string|max:500',
            'profession_name' => 'nullable|string|max:500',
            'rank' => 'nullable|string|max:100',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'hours' => 'nullable|integer',
            'theory_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'practice_hours' => 'nullable|integer',
            'listener_category' => 'nullable|string|max:500',
            'group_size' => 'nullable|string|max:100',
            'control_form' => 'nullable|string|max:300',
            'commission_type' => 'nullable|string|max:100',
            'document_type' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
            'uchipro' => 'nullable|string|max:300',
            'info_system' => 'nullable|string|max:300',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_fio' => 'nullable|string|max:500',
        ]);

        MatrixPo::create($validated);

        return redirect()->route('matrices.po.index')
            ->with('success', 'Запись ПО добавлена успешно.');
    }

    // ИЗМЕНЕНО: $matrixPo -> $po
    public function edit(MatrixPo $po): View
    {
        return view('matrices.po.edit', compact('po'));
    }

    // ИЗМЕНЕНО: $matrixPo -> $po
    public function update(Request $request, MatrixPo $po): RedirectResponse
    {
        $validated = $request->validate([
            'number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'education_type' => 'nullable|string|max:500',
            'profession_name' => 'nullable|string|max:500',
            'rank' => 'nullable|string|max:100',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string',
            'hours' => 'nullable|integer',
            'theory_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'practice_hours' => 'nullable|integer',
            'listener_category' => 'nullable|string|max:500',
            'group_size' => 'nullable|string|max:100',
            'control_form' => 'nullable|string|max:300',
            'commission_type' => 'nullable|string|max:100',
            'document_type' => 'nullable|string|max:200',
            'notes' => 'nullable|string|max:500',
            'uchipro' => 'nullable|string|max:300',
            'info_system' => 'nullable|string|max:300',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_fio' => 'nullable|string|max:500',
        ]);

        $po->update($validated);

        return redirect()->route('matrices.po.index')
            ->with('success', 'Запись ПО обновлена успешно.');
    }

    // ИЗМЕНЕНО: $matrixPo -> $po
    public function destroy(MatrixPo $po): RedirectResponse
    {
        $po->delete();

        return redirect()->route('matrices.po.index')
            ->with('success', 'Запись ПО удалена успешно.');
    }
}