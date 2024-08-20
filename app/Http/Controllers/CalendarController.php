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
}
