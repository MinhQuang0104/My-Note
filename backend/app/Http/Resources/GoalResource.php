<?php

namespace App\Http\Resources;

use App\Models\Goal;
use App\Models\GoalEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $goal = $this->goal();
        $lastEntry = $goal->relationLoaded('entries')
            ? $goal->entries->sortByDesc('log_date')->first()
            : null;

        $progressSummary = $this->when($goal->relationLoaded('entries'), [
            'current_streak' => $this->currentStreak($goal),
            'last_completed' => $lastEntry?->log_date?->format('Y-m-d'),
            'total_entries' => $goal->entries->count(),
        ]);

        return [
            'id' => $goal->id,
            'name' => $goal->name,
            'description' => $goal->description,
            'type' => $goal->type,
            'target_value' => $goal->target_value !== null ? (float) $goal->target_value : null,
            'unit' => $goal->unit,
            'repeat_rule' => $goal->repeat_rule,
            'start_date' => $goal->start_date->format('Y-m-d'),
            'end_date' => $goal->end_date?->format('Y-m-d'),
            'is_active' => $goal->is_active,
            'color' => $goal->color,
            'icon' => $goal->icon,
            'tags' => $goal->tags ?? [],
            'created_at' => $goal->created_at?->toISOString(),
            'updated_at' => $goal->updated_at?->toISOString(),
            'progress_summary' => $progressSummary,
        ];
    }

    private function currentStreak(Goal $goal): int
    {
        if (! $goal->relationLoaded('entries')) {
            return 0;
        }

        $dailyTotals = [];

        foreach ($goal->entries as $entry) {
            $date = $entry->log_date->format('Y-m-d');
            $dailyTotals[$date] = ($dailyTotals[$date] ?? 0) + (float) $entry->value;
        }

        return collect($dailyTotals)
            ->filter(fn (float $total): bool => $goal->target_value === null || $total >= (float) $goal->target_value)
            ->count();
    }

    private function goal(): Goal
    {
        return $this->resource;
    }
}
