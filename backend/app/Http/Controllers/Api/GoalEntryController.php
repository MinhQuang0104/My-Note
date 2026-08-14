<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\GoalEntry;
use Illuminate\Http\Request;

class GoalEntryController extends Controller
{
    public function index(Request $request, Goal $goal)
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        return response()->json($goal->entries()->latest()->get());
    }

    public function store(Request $request, Goal $goal)
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        $entry = $goal->entries()->create($request->validate([
            'label' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'entry_date' => ['required', 'date'],
        ]) + ['user_id' => $request->user()->id]);

        return response()->json($entry, 201);
    }

    public function update(Request $request, Goal $goal, GoalEntry $goalEntry)
    {
        abort_unless($goal->user_id === auth()->id() && $goalEntry->goal_id === $goal->id, 403);
        $goalEntry->update($request->validate([
            'label' => ['sometimes', 'required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'entry_date' => ['sometimes', 'required', 'date'],
        ]));

        return response()->json($goalEntry);
    }

    public function destroy(Goal $goal, GoalEntry $goalEntry)
    {
        abort_unless($goal->user_id === auth()->id() && $goalEntry->goal_id === $goal->id, 403);
        $goalEntry->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
