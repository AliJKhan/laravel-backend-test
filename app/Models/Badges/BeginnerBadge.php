<?php

namespace App\Models\Badges;
use App\Models\Badge;

class BeginnerBadge extends Badge
{
    public static string $BADGE_TITLE = 'Beginner';
    public static int $UNLOCKS_AT = 0;
}
