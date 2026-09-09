<?php

namespace App\Http\Controllers;

use App\Models\RequestsEventsType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RequestsEventsTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $eventsTypes = RequestsEventsType::when($search, function ($query, $search) {
            return $query->whereRaw('LOWER("name") LIKE ?', ['%' . mb_strtolower($search) . '%']);
        })
        ->orderBy('name')
        ->paginate(20);

        return view('directories.events-type.index', compact('eventsTypes'));
    }

    public function create(): View
    {
        return view('directories.events-type.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_events_types,name',
        ]);

        RequestsEventsType::create($validated);

        return redirect()->route('directories.events-type.index')
            ->with('success', 'Тип мероприятия добавлен успешно.');
    }

    public function edit(RequestsEventsType $eventsType): View
    {
        return view('directories.events-type.edit', compact('eventsType'));
    }

    public function update(Request $request, RequestsEventsType $eventsType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500|unique:requests_events_types,name,' . $eventsType->id,
        ]);

        $eventsType->update($validated);

        return redirect()->route('directories.events-type.index')
            ->with('success', 'Тип мероприятия обновлён успешно.');
    }

    public function destroy(RequestsEventsType $eventsType): RedirectResponse
    {
        $eventsType->delete();

        return redirect()->route('directories.events-type.index')
            ->with('success', 'Тип мероприятия удалён успешно.');
    }
}