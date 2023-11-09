<?php

namespace Tests\Feature;


use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
use App\Models\Badges\AdvancedBadge;
use App\Models\Badges\IntermediateBadge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use App\Repositories\UserAchievementRepository;
use App\Repositories\UserBadgeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationFeatureTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test complete application with multiple users and badges
     */
    public function test_the_application_with_multiple_users_multiple_badges(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(8))
            ->create();

        $second_user = User::factory()
            ->has(Comment::factory()->count(20))
            ->create();

        $lessons = Lesson::all()->take(22);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository = new UserBadgeRepository();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository);
            $listener->handle($event);
        }

        $lessons = Lesson::all()->take(38);

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $second_user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository);
            $listener->handle($event);
        }

        $this->assertEquals(22, $user->watched()->count());
        $this->assertEquals(8, $user->comments()->count());
        $this->assertEquals(6, $user->achievements()->count());
        $this->assertEquals(IntermediateBadge::$BADGE_TITLE, $user->badges()->latest('badge_id')->first()->title);

        $this->assertEquals(38, $second_user->watched()->count());
        $this->assertEquals(8, $user->comments()->count());
        $this->assertEquals(9, $second_user->achievements()->count());
        $this->assertEquals(AdvancedBadge::$BADGE_TITLE, $second_user->badges()->latest('badge_id')->first()->title);


    }

}
