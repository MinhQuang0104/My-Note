<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $note = $this->note();

        return [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $this->when($request->routeIs('notes.show', 'notes.update'), $note->content),
            'excerpt' => $this->when(! $request->routeIs('notes.show', 'notes.update'), str($note->content ?? '')->limit(120)->toString()),
            'tags' => $note->tags ?? [],
            'is_archived' => $note->is_archived,
            'created_at' => $note->created_at?->toISOString(),
            'updated_at' => $note->updated_at?->toISOString(),
        ];
    }

    private function note(): Note
    {
        return $this->resource;
    }
}
