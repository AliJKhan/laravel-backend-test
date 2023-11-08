<?php

namespace App\Models\Badges;
use App\Models\Badge;

class IntermediateBadge extends Badge
{

    public static string $BADGE_TITLE = 'Intermediate';
    public static int $UNLOCKS_AT = 4;

}
