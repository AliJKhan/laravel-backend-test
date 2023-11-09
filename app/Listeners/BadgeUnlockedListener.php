<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\Badge;

class BadgeUnlockedListener
{

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        $badge = Badge::where('title',$event->badge_name)->first();
        if(!$event->user->badges()->where('badge_id',$badge->id)->first()){
            $event->user->badges()->attach($badge);
        }
    }
}
