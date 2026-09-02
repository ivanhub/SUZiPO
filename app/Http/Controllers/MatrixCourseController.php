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
        
        $matrixCourses = MatrixCourse::when($search, function ($query, $search) {
            return $query->where('program_name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('program', 'like', "%{$search}%");
        })
        ->orderBy('number')
        ->paginate(20);

        return view('matrices.courses.index', compact('matrixCourses', 'search'));
    }

    public function create(): View
    {
        return view('matrices.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'program' => 'nullable|string|max:500',
            'number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'education_type' => 'nullable|string|max:500',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string|max:500',
            'hours' => 'nullable|integer',
            'theory_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'practice_hours' => 'nullable|integer',
            'listener_category' => 'nullable|string|max:500',
            'group_size' => 'nullable|string|max:100',
            'control_form' => 'nullable|string|max:200',
            'commission_type' => 'nullable|string|max:100',
            'document_type' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
            'uchipro' => 'nullable|string|max:200',
            'info_system' => 'nullable|string|max:500',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_fio' => 'nullable|string|max:500',
        ]);

        MatrixCourse::create($validated);

        return redirect()->route('matrices.courses.index')
            ->with('success', 'Запись курса добавлена успешно.');
    }

    public function edit(MatrixCourse $course): View
    {
        return view('matrices.courses.edit', compact('course'));
    }

    public function update(Request $request, MatrixCourse $course): RedirectResponse
    {
        $validated = $request->validate([
            'program' => 'nullable|string|max:500',
            'number' => 'nullable|integer',
            'code' => 'nullable|string|max:50',
            'education_type' => 'nullable|string|max:500',
            'program_name' => 'nullable|string',
            'full_name' => 'nullable|string',
            'study_form' => 'nullable|string|max:500',
            'hours' => 'nullable|integer',
            'theory_hours' => 'nullable|integer',
            'self_study_hours' => 'nullable|integer',
            'practical_hours' => 'nullable|integer',
            'practice_hours' => 'nullable|integer',
            'listener_category' => 'nullable|string|max:500',
            'group_size' => 'nullable|string|max:100',
            'control_form' => 'nullable|string|max:200',
            'commission_type' => 'nullable|string|max:100',
            'document_type' => 'nullable|string|max:200',
            'notes' => 'nullable|string',
            'uchipro' => 'nullable|string|max:200',
            'info_system' => 'nullable|string|max:500',
            'teacher_requirements' => 'nullable|string',
            'equipment' => 'nullable|string',
            'equipment_location' => 'nullable|string',
            'teacher_fio' => 'nullable|string|max:500',
        ]);

        $course->update($validated);

        return redirect()->route('matrices.courses.index')
            ->with('success', 'Запись курса обновлена успешно.');
    }

    public function destroy(MatrixCourse $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('matrices.courses.index')
            ->with('success', 'Запись курса удалена успешно.');
    }
}