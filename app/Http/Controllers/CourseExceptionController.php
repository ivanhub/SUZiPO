<?php

namespace App\Http\Controllers;

use App\Models\CourseException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CourseExceptionController extends Controller
{
    public function index(): View
    {
        $exceptions = CourseException::orderBy('course_name')->paginate(15);
        return view('course_exceptions.index', compact('exceptions'));
    }

    public function create(): View
    {
        return view('course_exceptions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:500|unique:course_exceptions,course_name',
        ]);

        CourseException::create($validated);

        return redirect()
            ->route('course-exceptions.index')
            ->with('success', 'Курс добавлен в исключения.');
    }

    public function edit(CourseException $courseException): View
    {
        return view('course_exceptions.edit', compact('courseException'));
    }

    public function update(Request $request, CourseException $courseException): RedirectResponse
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:500|unique:course_exceptions,course_name,' . $courseException->id,
        ]);

        $courseException->update($validated);

        return redirect()
            ->route('course-exceptions.index')
            ->with('success', 'Курс обновлен.');
    }

    public function destroy(CourseException $courseException): RedirectResponse
    {
        $courseException->delete();

        return redirect()
            ->route('course-exceptions.index')
            ->with('success', 'Курс удален из исключений.');
    }
}