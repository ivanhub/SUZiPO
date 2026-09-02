<?php

namespace App\Http\Controllers;

use App\Models\RequestsLearningType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsLearningTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $learningTypes = RequestsLearningType::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.learning-types.index', compact('learningTypes'));
    }

    public function create(): View
    {
        return view('directories.learning-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learning_types,name',
        ]);

        RequestsLearningType::create($validated);

        return redirect()->route('directories.learning-types.index')
            ->with('success', 'Тип обучения добавлен успешно.');
    }

    public function edit(RequestsLearningType $learningType): View
    {
        return view('directories.learning-types.edit', compact('learningType'));
    }

    public function update(Request $request, RequestsLearningType $learningType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learning_types,name,' . $learningType->id,
        ]);

        $learningType->update($validated);

        return redirect()->route('directories.learning-types.index')
            ->with('success', 'Тип обучения обновлён успешно.');
    }

    public function destroy(RequestsLearningType $learningType): RedirectResponse
    {
        $learningType->delete();

        return redirect()->route('directories.learning-types.index')
            ->with('success', 'Тип обучения удалён успешно.');
    }
}