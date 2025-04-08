<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function show(Request $request): Response
    {
        // Haal alleen de events op die behoren tot de ingelogde gebruiker
        $events = Event::where('user_id', $request->user()->id)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'date' => $event->date->format('Y-m-d'),
                    'time' => $event->time,
                    'title' => $event->title,
                    'user_id' => $event->user_id,
                    'confirmed' => $event->confirmed,
                ];
            });

        return Inertia::render('Calendar', [
            'events' => $events,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|string',
            'title' => 'required|string|max:255',
            'confirmed' => 'required|boolean',
        ]);

        // Voeg de user_id toe aan het event
        $event = Event::create([
            'user_id' => $request->user()->id,
            'date' => $request->date,
            'time' => $request->time,
            'title' => $request->title,
            'confirmed' => $request->confirmed,
        ]);

        return back();
    }


    public function update(Request $request, Event $event): RedirectResponse
    {
        // Controleer of de ingelogde gebruiker de eigenaar is
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->update($request->all());

        return back();
    }


    public function destroy(Request $request, Event $event): RedirectResponse
    {
        // Controleer of de ingelogde gebruiker de eigenaar is
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->delete();

        return back();
    }

}
