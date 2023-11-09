<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public string $achievement_name;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct($achievement_name, User $user)
    {
        $this->achievement_name = $achievement_name;
        $this->user = $user;
    }

}
