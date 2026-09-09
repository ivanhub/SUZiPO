<?php

namespace App\Http\Controllers;

use App\Models\RequestsCity;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsCityController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $cities = RequestsCity::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("city") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('city')
        ->paginate(20);

        return view('directories.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('directories.cities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'option_value' => 'nullable|integer|unique:requests_cities,option_value',
            'city' => 'required|string|max:255|unique:requests_cities,city',
        ]);

        RequestsCity::create($validated);

        return redirect()->route('directories.cities.index')
            ->with('success', 'Город добавлен успешно.');
    }

    public function edit(RequestsCity $city): View
    {
        return view('directories.cities.edit', compact('city'));
    }

    public function update(Request $request, RequestsCity $city): RedirectResponse
    {
        $validated = $request->validate([
            'option_value' => 'nullable|integer|unique:requests_cities,option_value,' . $city->id,
            'city' => 'required|string|max:255|unique:requests_cities,city,' . $city->id,
        ]);

        $city->update($validated);

        return redirect()->route('directories.cities.index')
            ->with('success', 'Город обновлён успешно.');
    }

    public function destroy(RequestsCity $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('directories.cities.index')
            ->with('success', 'Город удалён успешно.');
    }
}