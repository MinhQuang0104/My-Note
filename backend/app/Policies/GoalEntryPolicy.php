<?php

namespace App\Policies;

use App\Models\GoalEntry;
use App\Models\User;

class GoalEntryPolicy
{
    public function view(User $user, GoalEntry $entry): bool { return $entry->user_id === $user->id; }
    public function update(User $user, GoalEntry $entry): bool { return $this->view($user, $entry); }
    public function delete(User $user, GoalEntry $entry): bool { return $this->view($user, $entry); }
}
