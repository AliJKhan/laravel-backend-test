<?php

namespace App\Interfaces;

use App\Models\User;

interface UserBadgeRepositoryInterface
{
    public function unlockUserBadge(User $user);
    public function remainingToUnlockNextBadge($next_badge, User $user);

}
