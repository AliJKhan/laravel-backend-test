<?php

namespace Tests\Unit;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Interfaces\UserAchievementRepositoryInterface;
use App\Interfaces\UserBadgeRepositoryInterface;
use App\Listeners\LessonWatchedListener;
use App\Models\Lesson;
use App\Models\User;
use App\Repositories\UserAchievementRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

    }
    /**
     * A basic test example.
     */
    public function test_lesson_watched_by_user(): void
    {
        $user = User::find(1);
        $lesson = Lesson::find(1);
        Event::fake([AchievementUnlocked::class,BadgeUnlocked::class]);

        $user_achievement_repository_mock = Mockery::mock(UserAchievementRepositoryInterface::class);
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_achievement_repository_mock->shouldReceive('unlockLessonAchievements')->once()->andReturnNull();
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->once()->andReturnNull();


        $event = new LessonWatched($lesson, $user);
        $listener = new LessonWatchedListener($user_achievement_repository_mock, $user_badge_repository_mock);
        $listener->handle($event);

        $this->assertEquals($lesson->title,$user->lessons()->first()->title);
        Event::assertNotDispatched(AchievementUnlocked::class);
        Event::assertNotDispatched(BadgeUnlocked::class);

    }
}
//php artisan test --testsuite=Unit
