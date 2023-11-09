<?php

namespace App\Models\Badges;
use App\Models\Badge;

class MasterBadge extends Badge
{
    public static string $BADGE_TITLE = 'Master';
    public static int $UNLOCKS_AT = 10;

}
