<?php

namespace App\Http\Controllers;

use App\Models\RequestsAudience;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsAudienceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $audiences = RequestsAudience::when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER("number") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("location") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("responsible_person") LIKE ?', ['%' . mb_strtolower($search) . '%'])
                  ->orWhereRaw('LOWER("seats") LIKE ?', ['%' . mb_strtolower($search) . '%']);
            });
        })
        ->orderBy('number')
        ->paginate(20);

        return view('directories.audiences.index', compact('audiences'));
    }

    public function create(): View
    {
        return view('directories.audiences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'number' => 'required|string|max:100',
            'location' => 'nullable|string|max:500',
            'responsible_person' => 'nullable|string|max:500',
            'seats' => 'nullable|string|max:50',
        ]);

        RequestsAudience::create($validated);

        return redirect()->route('directories.audiences.index')
            ->with('success', 'Аудитория добавлена успешно.');
    }

    public function edit(RequestsAudience $audience): View
    {
        return view('directories.audiences.edit', compact('audience'));
    }

    public function update(Request $request, RequestsAudience $audience): RedirectResponse
    {
        $validated = $request->validate([
            'number' => 'required|string|max:100',
            'location' => 'nullable|string|max:500',
            'responsible_person' => 'nullable|string|max:500',
            'seats' => 'nullable|string|max:50',
        ]);

        $audience->update($validated);

        return redirect()->route('directories.audiences.index')
            ->with('success', 'Аудитория обновлена успешно.');
    }

    public function destroy(RequestsAudience $audience): RedirectResponse
    {
        $audience->delete();

        return redirect()->route('directories.audiences.index')
            ->with('success', 'Аудитория удалена успешно.');
    }
}