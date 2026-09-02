<?php

namespace App\Http\Controllers;

use App\Models\RequestsLearnReason;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsLearnReasonController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $reasons = RequestsLearnReason::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.learn-reasons.index', compact('reasons'));
    }

    public function create(): View
    {
        return view('directories.learn-reasons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learn_reasons,name',
        ]);

        RequestsLearnReason::create($validated);

        return redirect()->route('directories.learn-reasons.index')
            ->with('success', 'Причина обучения добавлена успешно.');
    }

    public function edit(RequestsLearnReason $learnReason): View
    {
        return view('directories.learn-reasons.edit', compact('learnReason'));
    }

    public function update(Request $request, RequestsLearnReason $learnReason): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_learn_reasons,name,' . $learnReason->id,
        ]);

        $learnReason->update($validated);

        return redirect()->route('directories.learn-reasons.index')
            ->with('success', 'Причина обучения обновлена успешно.');
    }

    public function destroy(RequestsLearnReason $learnReason): RedirectResponse
    {
        $learnReason->delete();

        return redirect()->route('directories.learn-reasons.index')
            ->with('success', 'Причина обучения удалена успешно.');
    }
}