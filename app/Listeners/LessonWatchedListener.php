<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Interfaces\UserAchievementRepositoryInterface;
use App\Interfaces\UserBadgeRepositoryInterface;
use App\Models\User;

class LessonWatchedListener
{

    private UserAchievementRepositoryInterface $userAchievementRepository;
    private UserBadgeRepositoryInterface $userBadgeRepository;

    /**
     * Create the event listener.
     */
    public function __construct(UserAchievementRepositoryInterface $userAchievementRepository, UserBadgeRepositoryInterface $userBadgeRepository)
    {
        $this->userAchievementRepository = $userAchievementRepository;
        $this->userBadgeRepository = $userBadgeRepository;
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

        $this->userAchievementRepository->unlockLessonAchievements($user);
        $this->userBadgeRepository->unlockUserBadge($user);

    }

}
