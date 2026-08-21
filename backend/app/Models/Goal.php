<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'type', 'target_value', 'unit',
        'repeat_rule', 'start_date', 'end_date', 'is_active', 'color', 'icon', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'target_value' => 'decimal:2',
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
            'is_active' => 'boolean',
            'tags' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        return $this->hasMany(GoalEntry::class);
    }

    public function entryStatusFor(string $date): string
    {
        $total = (float) $this->entries()->whereDate('log_date', $date)->sum('value');

        if ($total <= 0) {
            return 'not_done';
        }

        return $this->target_value !== null && $total < (float) $this->target_value
            ? 'partial'
            : 'completed';
    }
}
