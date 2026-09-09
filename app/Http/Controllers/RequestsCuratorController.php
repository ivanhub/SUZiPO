<?php

namespace App\Http\Controllers;

use App\Models\RequestsCurator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsCuratorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $curators = RequestsCurator::when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER("fio") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("profession") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("phone") LIKE ?', ['%' . mb_strtolower($search) . '%']);
            });
        })
        ->orderBy('fio')
        ->paginate(20);

        return view('directories.curators.index', compact('curators'));
    }

    public function create(): View
    {
        return view('directories.curators.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => 'required|string|max:500',
            'profession' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:100',
        ]);

        RequestsCurator::create($validated);

        return redirect()->route('directories.curators.index')
            ->with('success', 'Куратор добавлен успешно.');
    }

    public function edit(RequestsCurator $curator): View
    {
        return view('directories.curators.edit', compact('curator'));
    }

    public function update(Request $request, RequestsCurator $curator): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => 'required|string|max:500',
            'profession' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:100',
        ]);

        $curator->update($validated);

        return redirect()->route('directories.curators.index')
            ->with('success', 'Куратор обновлён успешно.');
    }

    public function destroy(RequestsCurator $curator): RedirectResponse
    {
        $curator->delete();

        return redirect()->route('directories.curators.index')
            ->with('success', 'Куратор удалён успешно.');
    }
}