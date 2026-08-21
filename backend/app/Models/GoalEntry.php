<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \Illuminate\Support\Carbon $log_date
 * @property string $value
 */
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

    /** @return BelongsTo<Goal, $this> */
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
