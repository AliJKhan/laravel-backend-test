<?php

namespace Tests\Unit;

use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
use App\Models\Badges\AdvancedBadge;
use App\Models\Badges\BeginnerBadge;
use App\Models\Badges\IntermediateBadge;
use App\Models\Badges\MasterBadge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use App\Repositories\UserAchievementRepository;
use App\Repositories\UserBadgeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BadgeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test user has beginner badge at start
     */
    public function test_user_has_beginner_badge(): void
    {
        $user = User::find(1);
        $user_badge_repository = new UserBadgeRepository();

        $next_badge = $user->badges()->latest('badge_id')->first()->next()->title;
        $remaining_to_unlock_next_badge = $user_badge_repository->remainingToUnlockNextBadge($next_badge, $user);

        $this->assertEquals(BeginnerBadge::$BADGE_TITLE,$user->badges()->first()->title);
        $this->assertEquals(0, $user->achievements()->count());
        $this->assertEquals(4, $remaining_to_unlock_next_badge);

    }

    /**
     * Test user unlocks badge by 4 achievements
     */
    public function test_user_unlocks_intermediate_badge(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all()->take(25);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository = new UserBadgeRepository();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository);
            $listener->handle($event);
        }

        $next_badge = $user->badges()->latest('badge_id')->first()->next()->title;
        $remaining_to_unlock_next_badge = $user_badge_repository->remainingToUnlockNextBadge($next_badge, $user);

        $this->assertEquals(4, $user->achievements()->count());
        $this->assertEquals("25 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);
        $this->assertEquals(IntermediateBadge::$BADGE_TITLE, $user->badges()->latest('badge_id')->first()->title);
        $this->assertEquals(4, $remaining_to_unlock_next_badge);
    }

    /**
     * Test user unlocks badge by 8 achievements
     */
    public function test_user_unlocks_advanced_badge(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(10))
            ->create();
        $lessons = Lesson::all()->take(25);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository = new UserBadgeRepository();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository);
            $listener->handle($event);
        }

        $next_badge = $user->badges()->latest('badge_id')->first()->next()->title;
        $remaining_to_unlock_next_badge = $user_badge_repository->remainingToUnlockNextBadge($next_badge, $user);

        $this->assertEquals(8, $user->achievements()->count());
        $this->assertEquals(AdvancedBadge::$BADGE_TITLE, $user->badges()->latest('badge_id')->first()->title);
        $this->assertEquals(2, $remaining_to_unlock_next_badge);
    }

    /**
     * Test user unlocks badge by 8 achievements
     */
    public function test_user_unlocks_master_badge(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(20))
            ->create();
        $lessons = Lesson::all()->take(50);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository = new UserBadgeRepository();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository);
            $listener->handle($event);
        }

        $this->assertEquals(10, $user->achievements()->count());
        $this->assertEquals(MasterBadge::$BADGE_TITLE, $user->badges()->latest('badge_id')->first()->title);
    }

}
