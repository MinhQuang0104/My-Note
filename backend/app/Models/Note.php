<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'tags', 'is_archived'];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_archived' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
