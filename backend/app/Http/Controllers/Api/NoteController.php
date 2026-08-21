<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        return $this->success(NoteResource::collection($request->user()->notes()->latest()->get()));
    }

    public function store(StoreNoteRequest $request)
    {
        $note = $request->user()->notes()->create($request->validated());
        return $this->success(new NoteResource($note), 'Note created successfully.', status: 201);
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return $this->success(new NoteResource($note));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);
        $note->update($request->validated());
        return $this->success(new NoteResource($note->refresh()), 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return $this->success(message: 'Note deleted successfully.');
    }
}
