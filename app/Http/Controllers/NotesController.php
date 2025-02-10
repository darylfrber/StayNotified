<?php

namespace App\Http\Controllers;

use App\Models\Note;
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

    public function show(Note $note)
    {
        // Verhoog het aantal views bij het openen van een notitie
        $note->increment('views');

        return Inertia::render('NoteDetail', [
            'note' => $note
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note = Note::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'last_edited_at' => now(),
        ]);

        return redirect()->route('notes.index');
    }

    public function update(Request $request, Note $note)
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

        return redirect()->route('notes.index');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index');
    }

    public function incrementViews(Note $note)
    {
        // Verhoog het aantal views
        $note->increment('views');

        // Retourneer een geldige Inertia respons met de bijgewerkte notitie
        return Inertia::render('UserNotes', [
            'notes' => Note::all() // Of een andere methode om je notities op te halen
        ]);
    }
}
