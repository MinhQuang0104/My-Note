<?php

namespace App\Http\Resources;

use App\Models\GoalEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $entry = $this->entry();

        return [
            'id' => $entry->id,
            'goal_id' => $entry->goal_id,
            'log_date' => $entry->log_date->format('Y-m-d'),
            'value' => (float) $entry->value,
            'status' => $entry->status,
            'note' => $entry->note,
            'created_at' => $entry->created_at?->toISOString(),
            'updated_at' => $entry->updated_at?->toISOString(),
        ];
    }

    private function entry(): GoalEntry
    {
        return $this->resource;
    }
}
