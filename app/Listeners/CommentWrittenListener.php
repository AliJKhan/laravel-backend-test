<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Interfaces\UserAchievementRepositoryInterface;
use App\Interfaces\UserBadgeRepositoryInterface;

class CommentWrittenListener
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
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = $comment->user;
        $this->userAchievementRepository->unlockCommentAchievements($user);
        $this->userBadgeRepository->unlockUserBadge($user);
    }

}
