<?php

namespace App\Http\Controllers;

use App\Interfaces\UserAchievementRepositoryInterface;
use App\Interfaces\UserBadgeRepositoryInterface;
use App\Models\User;

class AchievementsController extends Controller
{
    private UserAchievementRepositoryInterface $userAchievementRepository;
    private UserBadgeRepositoryInterface $userBadgeRepository;

    public function __construct(UserAchievementRepositoryInterface $userAchievementRepository, UserBadgeRepositoryInterface $userBadgeRepository)
    {
        $this->userAchievementRepository = $userAchievementRepository;
        $this->userBadgeRepository = $userBadgeRepository;
    }
    public function index(User $user)
    {
        $unlocked_achievements = $user->achievements()->pluck('title')->toArray();
        $current_badge = $user->badges()->latest('badge_id')->first()->title;
        $next_badge = ($user->badges()->latest('badge_id')->first()->next() ? $user->badges()->latest('badge_id')->first()->next()->title : '');
        $next_available_achievements = $this->userAchievementRepository->nextAvailableAchievements($user);
        $remaining_to_unlock_next_badge = $this->userBadgeRepository->remainingToUnlockNextBadge($next_badge, $user);

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => $current_badge,
            'next_badge' => $next_badge,
            'remaining_to_unlock_next_badge' => $remaining_to_unlock_next_badge
        ]);
    }
}
