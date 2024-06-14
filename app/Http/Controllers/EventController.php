<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(): Response
    {
        $events = Event::all();

        return Inertia::render('Calendar', [
            'events' => $events,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'title' => 'required|string',
            'confirmed' => 'required|boolean',
        ]);

        $event = Event::create([
            'date' => $request->date,
            'time' => $request->time,
            'title' => $request->title,
            'confirmed' => $request->confirmed,
        ]);

        return back()->with('event', $event);
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'title' => 'required|string',
            'confirmed' => 'required|boolean',
        ]);

        $event->update($request->all());

        return back()->with('event', $event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return back();
    }
}
