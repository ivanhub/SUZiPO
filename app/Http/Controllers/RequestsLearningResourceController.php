<?php

namespace App\Http\Controllers;

use App\Models\RequestsLearningResource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsLearningResourceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $resources = RequestsLearningResource::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.learning-resources.index', compact('resources'));
    }

    public function create(): View
    {
        return view('directories.learning-resources.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learning_resources,name',
        ]);

        RequestsLearningResource::create($validated);

        return redirect()->route('directories.learning-resources.index')
            ->with('success', 'Ресурс обучения добавлен успешно.');
    }

    public function edit(RequestsLearningResource $learningResource): View
    {
        return view('directories.learning-resources.edit', compact('learningResource'));
    }

    public function update(Request $request, RequestsLearningResource $learningResource): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learning_resources,name,' . $learningResource->id,
        ]);

        $learningResource->update($validated);

        return redirect()->route('directories.learning-resources.index')
            ->with('success', 'Ресурс обучения обновлён успешно.');
    }

    public function destroy(RequestsLearningResource $learningResource): RedirectResponse
    {
        $learningResource->delete();

        return redirect()->route('directories.learning-resources.index')
            ->with('success', 'Ресурс обучения удалён успешно.');
    }
}