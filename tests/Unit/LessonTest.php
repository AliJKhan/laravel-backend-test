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
     * Test lesson watched by user
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

    /**
     * Test single achievement unlocked from one lesson
     */
    public function test_achievement_unlocked_by_single_lesson(): void
    {
        $user = User::find(1);
        $lesson = Lesson::find(1);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->once()->andReturnNull();


        $event = new LessonWatched($lesson, $user);
        $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
        $listener->handle($event);

        $this->assertEquals(1, $user->watched()->count());
        $this->assertEquals(1, $user->achievements()->count());
        $this->assertEquals("First Lesson Watched", $user->achievements()->first()->title);
    }

    /**
     * Test single achievement unlocked from same lesson
     */
    public function test_achievement_not_unlocked_by_same_lesson(): void
    {
        $user = User::find(1);
        $lesson = Lesson::find(1);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->twice()->andReturnNull();


        $event = new LessonWatched($lesson, $user);
        $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
        $listener->handle($event);

        $this->assertEquals(1, $user->watched()->count());
        $this->assertEquals(1, $user->achievements()->count());
        $this->assertEquals("First Lesson Watched", $user->achievements()->first()->title);

        $event = new LessonWatched($lesson, $user);
        $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
        $listener->handle($event);

        $this->assertEquals(1, $user->watched()->count());
        $this->assertEquals(1, $user->achievements()->count());
        $this->assertEquals("First Lesson Watched", $user->achievements()->first()->title);
    }


    /**
     * Test multiple lessons watched by same user
     */
    public function test_multiple_lesson_watched_same_user(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all()->take(5);
        Event::fake([AchievementUnlocked::class,BadgeUnlocked::class]);

        $user_achievement_repository_mock = Mockery::mock(UserAchievementRepositoryInterface::class);
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_achievement_repository_mock->shouldReceive('unlockLessonAchievements')->times(5)->andReturnNull();
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(5)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository_mock, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(5, $user->watched()->count());
        Event::assertNotDispatched(AchievementUnlocked::class);
        Event::assertNotDispatched(BadgeUnlocked::class);

    }

    /**
     * Test two achievement unlocked from five lessons
     */
    public function test_two_achievements_unlocked_by_five_lessons(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all()->take(7);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(7)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(7, $user->watched()->count());
        $this->assertEquals(2, $user->achievements()->count());
        $this->assertEquals("5 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }

    /**
     * Test two achievement unlocked from five lesson
     */
    public function test_three_achievements_unlocked_by_ten_lessons(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all()->take(14);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(14)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(14, $user->watched()->count());
        $this->assertEquals(3, $user->achievements()->count());
        $this->assertEquals("10 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);
    }

    /**
     * Test two achievement unlocked from five lesson
     */
    public function test_four_achievements_unlocked_by_25_lessons(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all()->take(44);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(44)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(44, $user->watched()->count());
        $this->assertEquals(4, $user->achievements()->count());
        $this->assertEquals("25 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);


    }

    /**
     * Test two achievement unlocked from five lesson
     */
    public function test_five_achievements_unlocked_by_50_lessons(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all();

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(50)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(50, $user->watched()->count());
        $this->assertEquals(5, $user->achievements()->count());
        $this->assertEquals("50 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);


    }

    /**
     * Test two achievement unlocked from five lesson
     */
    public function test_no_achievements_unlocked_by_same_lessons(): void
    {
        $user = User::find(1);
        $lessons = Lesson::all();

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(100)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $this->assertEquals(50, $user->watched()->count());
        $this->assertEquals(5, $user->achievements()->count());
        $this->assertEquals("50 Lessons Watched", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }
}
