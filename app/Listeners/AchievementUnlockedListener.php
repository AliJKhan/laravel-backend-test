<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;

class AchievementUnlockedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlocked $event): void
    {
        $achievement = Achievement::where('title',$event->achievement_name)->first();

        if(!$this->checkIfAchievementUnlocked($achievement, $event->user))
            $event->user->achievements()->attach($achievement);
    }

    /**
     * Check if user has already unlocked achievement
     */
    private function checkIfAchievementUnlocked($achievement, User $user)
    {
        return $user->achievements()->where('achievement_id',$achievement->id)->first();
    }
}
