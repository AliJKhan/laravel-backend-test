<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Interfaces\UserBadgeRepositoryInterface;
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
use Mockery;
use Tests\TestCase;

class FeatureTests extends TestCase
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
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200);
    }

    /**
     * Test response with 1 unlocked achievement
     */
    public function test_response_with_1_lesson_achievement(): void
    {
        $user = User::find(1);
        $lesson = Lesson::find(1);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->once()->andReturnNull();

        $event = new LessonWatched($lesson, $user);
        $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
        $listener->handle($event);

        $achievements_array = ['First Lesson Watched'];
        $next_achievements_array = ['5 Lessons Watched' , 'First Comment Written'];

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $achievements_array,
                'next_available_achievements' => $next_achievements_array,
                'current_badge' => BeginnerBadge::$BADGE_TITLE,
                'next_badge' => IntermediateBadge::$BADGE_TITLE,
                'remaining_to_unlock_next_badge' => 3
            ]);;
    }

    /**
     * Test response with 1 unlocked achievement
     */
    public function test_response_with_1_comment_achievement(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(1))
            ->create();

        $achievements_array = ['First Comment Written'];
        $next_achievements_array = ['First Lesson Watched' , '3 Comments Written'];

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $achievements_array,
                'next_available_achievements' => $next_achievements_array,
                'current_badge' => BeginnerBadge::$BADGE_TITLE,
                'next_badge' => IntermediateBadge::$BADGE_TITLE,
                'remaining_to_unlock_next_badge' => 3
            ]);;
    }

    /**
     * Test response with multiple unlocked achievements
     */
    public function test_response_with_multiple_achievements(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(10))
            ->create();

        $lessons = Lesson::all()->take(5);

        $user_achievement_repository = new UserAchievementRepository();
        $user_badge_repository_mock = Mockery::mock(UserBadgeRepositoryInterface::class);
        $user_badge_repository_mock->shouldReceive('unlockUserBadge')->times(5)->andReturnNull();

        foreach ($lessons as $lesson){
            $event = new LessonWatched($lesson, $user);
            $listener = new LessonWatchedListener($user_achievement_repository, $user_badge_repository_mock);
            $listener->handle($event);
        }

        $achievements_array = ['First Comment Written','3 Comments Written','5 Comments Written','10 Comments Written','First Lesson Watched','5 Lessons Watched'];
        $next_achievements_array = ['10 Lessons Watched' , '20 Comments Written'];

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $achievements_array,
                'next_available_achievements' => $next_achievements_array,
                'current_badge' => IntermediateBadge::$BADGE_TITLE,
                'next_badge' => AdvancedBadge::$BADGE_TITLE,
                'remaining_to_unlock_next_badge' => 2
            ]);;
    }

    /**
     * Test response with all unlocked achievements
     */
    public function test_response_with_all_achievements(): void
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

        $achievements_array = ['First Comment Written','3 Comments Written','5 Comments Written','10 Comments Written','20 Comments Written','First Lesson Watched','5 Lessons Watched','10 Lessons Watched','25 Lessons Watched','50 Lessons Watched'];
        $next_achievements_array = ['' , ''];

        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertStatus(200)
            ->assertJson([
                'unlocked_achievements' => $achievements_array,
                'next_available_achievements' => $next_achievements_array,
                'current_badge' => MasterBadge::$BADGE_TITLE,
                'next_badge' => '',
                'remaining_to_unlock_next_badge' => 0
            ]);;
    }

}
