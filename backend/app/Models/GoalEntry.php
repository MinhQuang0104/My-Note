<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalEntry extends Model
{
    protected $fillable = ['goal_id', 'user_id', 'log_date', 'value', 'note', 'status'];

    protected function casts(): array
    {
        return [
            'log_date' => 'date:Y-m-d',
            'value' => 'decimal:2',
        ];
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
