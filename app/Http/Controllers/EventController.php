<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
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

        return back()->with('event', $event);
    }


    public function update(Request $request, Event $event): RedirectResponse
    {
        // Controleer of de ingelogde gebruiker de eigenaar is
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->update($request->all());

        return back()->with('event', $event);
    }


    public function destroy(Request $request, Event $event): RedirectResponse
    {
        // Controleer of de ingelogde gebruiker de eigenaar is
        if ($event->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $event->delete();

        return back()->with('event', $event);
    }

}
