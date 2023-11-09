<?php

namespace App\Repositories;

use App\Events\BadgeUnlocked;
use App\Interfaces\UserBadgeRepositoryInterface;
use App\Models\Badges\AdvancedBadge;
use App\Models\Badges\BeginnerBadge;
use App\Models\Badges\IntermediateBadge;
use App\Models\Badges\MasterBadge;
use App\Models\User;

class UserBadgeRepository implements UserBadgeRepositoryInterface
{

    /**
     * @return void
     * Check achievement count and update badges accordingly
     */
    public function unlockUserBadge(User $user): void
    {
        $achievements_count = $user->achievements()->count();

        switch (true) {
            case $achievements_count < IntermediateBadge::$UNLOCKS_AT:
                event(new BadgeUnlocked(BeginnerBadge::$BADGE_TITLE,$user));
                break;

            case $achievements_count === IntermediateBadge::$UNLOCKS_AT:
                event(new BadgeUnlocked(IntermediateBadge::$BADGE_TITLE,$user));
                break;

            case $achievements_count === AdvancedBadge::$UNLOCKS_AT:
                event(new BadgeUnlocked(AdvancedBadge::$BADGE_TITLE,$user));
                break;

            case $achievements_count === MasterBadge::$UNLOCKS_AT:
                event(new BadgeUnlocked(MasterBadge::$BADGE_TITLE,$user));
                break;

            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }
    }

    /**
     * @return int
     * Returns number of achievements needed to earn next badge
     */
    public function remainingToUnlockNextBadge($next_badge, User $user): int
    {
        $achievements_count = $user->achievements()->count();
        $remaining_to_unlock_next_badge = 0;
        switch ($next_badge) {
            case BeginnerBadge::$BADGE_TITLE:
                $remaining_to_unlock_next_badge = BeginnerBadge::$UNLOCKS_AT - $achievements_count;
                break;

            case IntermediateBadge::$BADGE_TITLE:
                $remaining_to_unlock_next_badge = IntermediateBadge::$UNLOCKS_AT - $achievements_count;
                break;

            case AdvancedBadge::$BADGE_TITLE:
                $remaining_to_unlock_next_badge = AdvancedBadge::$UNLOCKS_AT - $achievements_count;
                break;

            case MasterBadge::$BADGE_TITLE:
                $remaining_to_unlock_next_badge = MasterBadge::$UNLOCKS_AT - $achievements_count;
                break;

            default:
                echo "Your favorite color is neither red, blue, nor green!";

           }
        return $remaining_to_unlock_next_badge;

    }
}
