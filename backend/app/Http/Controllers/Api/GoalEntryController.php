<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGoalEntryRequest;
use App\Http\Requests\UpdateGoalEntryRequest;
use App\Http\Resources\GoalEntryResource;
use App\Models\Goal;
use App\Models\GoalEntry;
use Illuminate\Http\Request;

class GoalEntryController extends Controller
{
    public function index(Request $request, Goal $goal)
    {
        $this->authorize('view', $goal);
        $query = $goal->entries()->orderByDesc('log_date')->orderByDesc('id');
        if ($request->filled('start_date')) $query->whereDate('log_date', '>=', $request->date('start_date'));
        if ($request->filled('end_date')) $query->whereDate('log_date', '<=', $request->date('end_date'));
        return $this->success(GoalEntryResource::collection($query->get()));
    }

    public function store(StoreGoalEntryRequest $request, Goal $goal)
    {
        $this->authorize('view', $goal);
        $entry = $goal->entries()->create($request->validated() + ['user_id' => $request->user()->id]);
        $this->refreshStatus($entry);
        return $this->success(new GoalEntryResource($entry->refresh()), 'Goal entry created successfully.', status: 201);
    }

    public function update(UpdateGoalEntryRequest $request, Goal $goal, GoalEntry $goalEntry)
    {
        $this->authorize('view', $goal);
        $this->authorize('update', $goalEntry);
        $goalEntry->update($request->validated());
        $this->refreshStatus($goalEntry);
        return $this->success(new GoalEntryResource($goalEntry->refresh()), 'Goal entry updated successfully.');
    }

    public function destroy(Goal $goal, GoalEntry $goalEntry)
    {
        $this->authorize('view', $goal);
        $this->authorize('delete', $goalEntry);
        $date = $goalEntry->log_date;
        $goalEntry->delete();
        $this->refreshStatusForDate($goal, $date);
        return $this->success(message: 'Goal entry deleted successfully.');
    }

    private function refreshStatus(GoalEntry $entry): void
    {
        $this->refreshStatusForDate($entry->goal, $entry->log_date);
    }

    private function refreshStatusForDate(Goal $goal, mixed $date): void
    {
        $entries = $goal->entries()->whereDate('log_date', $date)->get();
        $total = (float) $entries->sum('value');
        $status = $total <= 0 ? 'not_done' : ($goal->target_value !== null && $total < (float) $goal->target_value ? 'partial' : 'completed');
        $goal->entries()->whereDate('log_date', $date)->update(['status' => $status]);
    }
}
