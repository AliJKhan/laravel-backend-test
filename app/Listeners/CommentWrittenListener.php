<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentWrittenListener
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
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = $comment->user;
        $this->unlockAchievements($user);
    }

    /**
     * @return void
     * Check user comment count and update achievements accordingly
     *
     */
    private function unlockAchievements(User $user): void
    {
        $comment_count = $user->comments()->count();

        switch (true) {
            case $comment_count === 1:
                event(new AchievementUnlocked('First Comment Written',$user));
                break;

            case $comment_count === 3:
                event(new AchievementUnlocked('3 Comments Written',$user));
                break;

            case $comment_count === 5:
                event(new AchievementUnlocked('5 Comments Written',$user));
                break;

            case $comment_count === 10:
                event(new AchievementUnlocked('10 Comments Written',$user));
                break;

            case $comment_count === 20:
                event(new AchievementUnlocked('20 Comments Written',$user));
                break;

            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }
    }
}
