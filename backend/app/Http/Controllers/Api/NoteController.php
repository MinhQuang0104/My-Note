<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->notes()->latest()->get());
    }

    public function store(Request $request)
    {
        $note = $request->user()->notes()->create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_archived' => ['nullable', 'boolean'],
        ]));

        return response()->json($note, 201);
    }

    public function show(Note $note)
    {
        abort_unless($note->user_id === auth()->id(), 403);

        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        abort_unless($note->user_id === auth()->id(), 403);

        $note->update($request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_archived' => ['nullable', 'boolean'],
        ]));

        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        abort_unless($note->user_id === auth()->id(), 403);
        $note->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
