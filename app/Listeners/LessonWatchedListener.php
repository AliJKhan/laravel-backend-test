<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
                    $achievement = Achievement::where('title','First Lesson Watched')->first();
                    if(!$this->checkIfAchievementUnlocked($achievement, $user))
                        $user->achievements()->attach($achievement);
                    break;

                case $lessonCount === 5:
                    $achievement = Achievement::where('title','5 Lessons Watched')->first();
                    if(!$this->checkIfAchievementUnlocked($achievement, $user))
                        $user->achievements()->attach($achievement);
                    break;

                case $lessonCount === 10:
                    $achievement = Achievement::where('title','10 Lessons Watched')->first();
                    if(!$this->checkIfAchievementUnlocked($achievement, $user))
                        $user->achievements()->attach($achievement);
                    break;

                case $lessonCount === 25:
                    $achievement = Achievement::where('title','25 Lessons Watched')->first();
                    if(!$this->checkIfAchievementUnlocked($achievement, $user))
                        $user->achievements()->attach($achievement);
                    break;

                case $lessonCount === 50:
                    $achievement = Achievement::where('title','50 Lessons Watched')->first();
                    if(!$this->checkIfAchievementUnlocked($achievement, $user))
                        $user->achievements()->attach($achievement);
                    break;

                default:
                    echo "Your favorite color is neither red, blue, nor green!";
        }
    }

    /**
     * Check if user has already unlocked achievement
     */
    private function checkIfAchievementUnlocked($achievement, User $user)
    {
        return $user->achievements()->where('achievement_id',$achievement->id)->first();
    }
}
