<?php

namespace App\Http\Controllers;

use App\Interfaces\UserAchievementRepositoryInterface;
use App\Models\User;

class AchievementsController extends Controller
{
    private UserAchievementRepositoryInterface $userAchievementRepository;

    public function __construct(UserAchievementRepositoryInterface $userAchievementRepository)
    {
        $this->userAchievementRepository = $userAchievementRepository;
    }
    public function index(User $user)
    {
        $unlocked_achievements = $user->achievements()->pluck('title')->toArray();
        $next_available_achievements = $this->userAchievementRepository->nextAvailableAchievements($user);

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => '',
            'next_badge' => '',
            'remaining_to_unlock_next_badge' => 0
        ]);
    }
}
