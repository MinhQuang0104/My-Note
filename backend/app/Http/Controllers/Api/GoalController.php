<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->goals()->latest();
        if ($request->has('active')) $query->where('is_active', $request->boolean('active'));
        if ($request->filled('repeat_rule')) $query->where('repeat_rule', $request->string('repeat_rule'));

        $goals = $query->paginate(min(max($request->integer('per_page', 20), 1), 100));
        return $this->success(GoalResource::collection($goals->getCollection()), meta: [
            'page' => $goals->currentPage(), 'per_page' => $goals->perPage(), 'total' => $goals->total(),
        ]);
    }

    public function store(StoreGoalRequest $request)
    {
        $goal = $request->user()->goals()->create($request->validated() + ['is_active' => $request->boolean('is_active', true)]);
        return $this->success(new GoalResource($goal), 'Goal created successfully.', status: 201);
    }

    public function show(Goal $goal)
    {
        $this->authorize('view', $goal);
        return $this->success(new GoalResource($goal->load('entries')));
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $this->authorize('update', $goal);
        $goal->update($request->validated());
        return $this->success(new GoalResource($goal->refresh()), 'Goal updated successfully.');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();
        return $this->success(message: 'Goal deleted successfully.');
    }

    public function disable(Goal $goal)
    {
        $this->authorize('update', $goal);
        $goal->update(['is_active' => false]);
        return $this->success(message: 'Goal disabled successfully.');
    }
}
