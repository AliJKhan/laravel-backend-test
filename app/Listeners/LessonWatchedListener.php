<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\User;

class LessonWatchedListener
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
    public function handle(LessonWatched $event): void
    {
        $lesson = $event->lesson;
        $user = $event->user;

        $record = $user->lessons()->where('lesson_id',$lesson->id)->first();
        if(!$record)
            $user->lessons()->attach($lesson->id,['watched'=>true]);

        $this->unlockAchievements($user);
    }

    /**
     * @return void
     * Check lessons watched and update achievements accordingly
     * TODO: make event handler for achievements
     */
    private function unlockAchievements(User $user): void
    {
        $lessonCount = $user->watched()->count();

            switch (true) {
                case $lessonCount === 1:
                    event(new AchievementUnlocked('First Lesson Watched',$user));
                    break;

                case $lessonCount === 5:
                    event(new AchievementUnlocked('5 Lessons Watched',$user));
                    break;

                case $lessonCount === 10:
                    event(new AchievementUnlocked('10 Lessons Watched',$user));
                    break;

                case $lessonCount === 25:
                    event(new AchievementUnlocked('20 Lessons Watched',$user));
                    break;

                case $lessonCount === 50:
                    event(new AchievementUnlocked('50 Lessons Watched',$user));
                    break;

                default:
                    echo "Your favorite color is neither red, blue, nor green!";
        }
    }
}
