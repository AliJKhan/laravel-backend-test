<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementType;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $unlocked_achievements = $user->achievements()->pluck('title')->toArray();
        $next_available_achievements = $user->nextAvailableAchievements();

        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $next_available_achievements,
            'current_badge' => '',
            'next_badge' => '',
            'remaining_to_unlock_next_badge' => 0
        ]);
    }
}
