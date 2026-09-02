<?php

namespace App\Http\Controllers;

use App\Models\RequestsCourse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsCourseController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $courses = RequestsCourse::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("course") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('course')
        ->paginate(20);

        return view('directories.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('directories.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'option_value' => 'nullable|integer|unique:requests_courses,option_value',
            'course' => 'required|string|max:500|unique:requests_courses,course',
        ]);

        RequestsCourse::create($validated);

        return redirect()->route('directories.courses.index')
            ->with('success', 'Курс добавлен успешно.');
    }

    public function edit(RequestsCourse $course): View
    {
        return view('directories.courses.edit', compact('course'));
    }

    public function update(Request $request, RequestsCourse $course): RedirectResponse
    {
        $validated = $request->validate([
            'option_value' => 'nullable|integer|unique:requests_courses,option_value,' . $course->id,
            'course' => 'required|string|max:500|unique:requests_courses,course,' . $course->id,
        ]);

        $course->update($validated);

        return redirect()->route('directories.courses.index')
            ->with('success', 'Курс обновлён успешно.');
    }

    public function destroy(RequestsCourse $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('directories.courses.index')
            ->with('success', 'Курс удалён успешно.');
    }
}