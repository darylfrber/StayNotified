<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotesController extends Controller
{
    public function index(Request $request): Response
    {
        $notes = Note::where('user_id', $request->user()->id)
            ->orderBy('last_edited_at', 'desc')
            ->get();

        return Inertia::render('Notes', [
            'notes' => $notes,
        ]);
    }

    public function show(Note $note): Response
    {
        // Verhoog het aantal views bij het openen van een notitie
        $note->increment('views');

        return Inertia::render('NoteDetail', [
            'note' => $note
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        Note::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'last_edited_at' => now(),
        ]);

        return back();
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
            'last_edited_at' => now(),
        ]);

        return back();
    }

    public function destroy(Note $note): RedirectResponse
    {
        $note->delete();
        return back();
    }
}
