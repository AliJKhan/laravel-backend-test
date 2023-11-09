<?php

namespace App\Interfaces;

use App\Models\User;

interface UserAchievementRepositoryInterface
{
    public function unlockLessonAchievements(User $user);
    public function unlockCommentAchievements(User $user);
    public function nextAvailableAchievements(User $user);

}
