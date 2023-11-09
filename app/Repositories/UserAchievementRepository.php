<?php

namespace App\Repositories;

use App\Events\AchievementUnlocked;
use App\Interfaces\UserAchievementRepositoryInterface;
use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\User;

class UserAchievementRepository implements UserAchievementRepositoryInterface
{
    /**
     * @return void
     * Check lessons watched count and update achievements accordingly
     */
    public function unlockLessonAchievements(User $user): void
    {
        $lesson_count = $user->watched()->count();
        switch (true) {
            case $lesson_count === 1:
                event(new AchievementUnlocked('First Lesson Watched',$user));
                break;

            case $lesson_count === 5:
                event(new AchievementUnlocked('5 Lessons Watched',$user));
                break;

            case $lesson_count === 10:
                event(new AchievementUnlocked('10 Lessons Watched',$user));
                break;

            case $lesson_count === 25:
                event(new AchievementUnlocked('25 Lessons Watched',$user));
                break;

            case $lesson_count === 50:
                event(new AchievementUnlocked('50 Lessons Watched',$user));
                break;

            default:
        }
    }

    /**
     * @return void
     * Check user comment count and update achievements accordingly
     */
    public function unlockCommentAchievements(User $user): void
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
        }
    }

    /**
     * @return array
     * The achievements user has yet to unlock
     */
    public function nextAvailableAchievements(User $user): array
    {
        $achievement_types = AchievementType::all();
        $achievement_type_ids = $user->achievements()->pluck('id')->toArray();
        $next_available_achievements = [];
        foreach($achievement_types as $type){
            $next_available_achievements[] = (Achievement::where('achievement_type_id', $type->id)->whereNotIn('id',$achievement_type_ids )->first() ? Achievement::where('achievement_type_id', $type->id)->whereNotIn('id',$achievement_type_ids )->first()->title: '');
        }
        return $next_available_achievements;
    }


}
