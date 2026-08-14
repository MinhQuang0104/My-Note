<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalEntry extends Model
{
    protected $fillable = ['goal_id', 'user_id', 'label', 'note', 'entry_date'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
