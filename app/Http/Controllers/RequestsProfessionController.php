<?php

namespace App\Http\Controllers;

use App\Models\RequestsProfession;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsProfessionController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $professions = RequestsProfession::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.professions.index', compact('professions'));
    }

    public function create(): View
    {
        return view('directories.professions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'nullable|integer|unique:requests_professions,value',
            'name' => 'required|string|max:500|unique:requests_professions,name',
        ]);

        RequestsProfession::create($validated);

        return redirect()->route('directories.professions.index')
            ->with('success', 'Профессия добавлена успешно.');
    }

    public function edit(RequestsProfession $profession): View
    {
        return view('directories.professions.edit', compact('profession'));
    }

    public function update(Request $request, RequestsProfession $profession): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'nullable|integer|unique:requests_professions,value,' . $profession->id,
            'name' => 'required|string|max:500|unique:requests_professions,name,' . $profession->id,
        ]);

        $profession->update($validated);

        return redirect()->route('directories.professions.index')
            ->with('success', 'Профессия обновлена успешно.');
    }

    public function destroy(RequestsProfession $profession): RedirectResponse
    {
        $profession->delete();

        return redirect()->route('directories.professions.index')
            ->with('success', 'Профессия удалена успешно.');
    }
}