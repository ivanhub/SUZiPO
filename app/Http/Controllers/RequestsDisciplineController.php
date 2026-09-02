<?php

namespace App\Http\Controllers;

use App\Models\RequestsDiscipline;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsDisciplineController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $disciplines = RequestsDiscipline::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.disciplines.index', compact('disciplines'));
    }

    public function create(): View
    {
        return view('directories.disciplines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_disciplines,name',
        ]);

        RequestsDiscipline::create($validated);

        return redirect()->route('directories.disciplines.index')
            ->with('success', 'Дисциплина добавлена успешно.');
    }

    public function edit(RequestsDiscipline $discipline): View
    {
        return view('directories.disciplines.edit', compact('discipline'));
    }

    public function update(Request $request, RequestsDiscipline $discipline): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_disciplines,name,' . $discipline->id,
        ]);

        $discipline->update($validated);

        return redirect()->route('directories.disciplines.index')
            ->with('success', 'Дисциплина обновлена успешно.');
    }

    public function destroy(RequestsDiscipline $discipline): RedirectResponse
    {
        $discipline->delete();

        return redirect()->route('directories.disciplines.index')
            ->with('success', 'Дисциплина удалена успешно.');
    }
}