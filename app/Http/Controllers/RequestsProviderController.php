<?php

namespace App\Http\Controllers;

use App\Models\RequestsProvider;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsProviderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $providers = RequestsProvider::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.providers.index', compact('providers'));
    }

    public function create(): View
    {
        return view('directories.providers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'nullable|integer|unique:requests_providers,value',
            'name' => 'required|string|max:500|unique:requests_providers,name',
        ]);

        RequestsProvider::create($validated);

        return redirect()->route('directories.providers.index')
            ->with('success', 'Учебное заведение добавлено успешно.');
    }

    public function edit(RequestsProvider $provider): View
    {
        return view('directories.providers.edit', compact('provider'));
    }

    public function update(Request $request, RequestsProvider $provider): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'nullable|integer|unique:requests_providers,value,' . $provider->id,
            'name' => 'required|string|max:500|unique:requests_providers,name,' . $provider->id,
        ]);

        $provider->update($validated);

        return redirect()->route('directories.providers.index')
            ->with('success', 'Учебное заведение обновлено успешно.');
    }

    public function destroy(RequestsProvider $provider): RedirectResponse
    {
        $provider->delete();

        return redirect()->route('directories.providers.index')
            ->with('success', 'Учебное заведение удалено успешно.');
    }
}