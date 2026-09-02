<?php

namespace App\Http\Controllers;

use App\Models\RequestsTeachers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsTeachersController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $teachers = RequestsTeachers::when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER("fio") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("profession") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("division1") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("division2") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("division3") LIKE ?', ['%' . mb_strtolower($search) . '%']);
            });
        })
        ->orderBy('fio')
        ->paginate(20);

        return view('directories.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('directories.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => 'required|string|max:500',
            'profession' => 'nullable|string|max:500',
            'division1' => 'nullable|string|max:500',
            'division2' => 'nullable|string|max:500',
            'division3' => 'nullable|string|max:500',
        ]);

        RequestsTeachers::create($validated);

        return redirect()->route('directories.teachers.index')
            ->with('success', 'Преподаватель добавлен успешно.');
    }

    public function edit(RequestsTeachers $teacher): View
    {
        return view('directories.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, RequestsTeachers $teacher): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => 'required|string|max:500',
            'profession' => 'nullable|string|max:500',
            'division1' => 'nullable|string|max:500',
            'division2' => 'nullable|string|max:500',
            'division3' => 'nullable|string|max:500',
        ]);

        $teacher->update($validated);

        return redirect()->route('directories.teachers.index')
            ->with('success', 'Преподаватель обновлён успешно.');
    }

    public function destroy(RequestsTeachers $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()->route('directories.teachers.index')
            ->with('success', 'Преподаватель удалён успешно.');
    }
}