<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;

class CalendarController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'date' => $event->date->format('Y-m-d'),
                'time' => $event->time,
                'title' => $event->title,
                'confirmed' => $event->confirmed,
            ];
        });

        return Inertia::render('Calendar', [
            'events' => $events,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Event::create($request->all());
        return Redirect::route('calendar.index');
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($request->all());
        return Redirect::route('calendar.index');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return Redirect::route('calendar.index');
    }
}
