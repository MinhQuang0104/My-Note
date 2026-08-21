<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lastEntry = $this->relationLoaded('entries')
            ? $this->entries->sortByDesc('log_date')->first()
            : null;

        $progressSummary = $this->when($this->relationLoaded('entries'), [
            'current_streak' => $this->currentStreak(),
            'last_completed' => $lastEntry?->log_date?->format('Y-m-d'),
            'total_entries' => $this->entries->count(),
        ]);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'target_value' => $this->target_value !== null ? (float) $this->target_value : null,
            'unit' => $this->unit,
            'repeat_rule' => $this->repeat_rule,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'is_active' => $this->is_active,
            'color' => $this->color,
            'icon' => $this->icon,
            'tags' => $this->tags ?? [],
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'progress_summary' => $progressSummary,
        ];
    }

    private function currentStreak(): int
    {
        if (! $this->relationLoaded('entries')) {
            return 0;
        }

        return $this->entries->groupBy(fn ($entry) => $entry->log_date->format('Y-m-d'))
            ->filter(fn ($entries) => $this->target_value === null || $entries->sum('value') >= $this->target_value)
            ->count();
    }
}
