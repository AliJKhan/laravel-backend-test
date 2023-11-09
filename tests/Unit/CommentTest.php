<?php

namespace Tests\Unit;

use App\Events\BadgeUnlocked;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test comment unlocks achievement
     */
    public function test_comment_unlocks_achievement(): void
    {
        $user = User::find(1);
        Comment::create(['user_id' => $user->id, 'body' => $this->faker->sentence(5)]);

        Event::fake([BadgeUnlocked::class]);

        $this->assertEquals(1, $user->achievements()->count());
        $this->assertEquals("First Comment Written", $user->achievements()->first()->title);
        Event::assertNotDispatched(BadgeUnlocked::class);

    }

    /**
     * Test two achievement unlocked from 3 comments
     */
    public function test_two_achievements_unlocked_by_3_comments(): void
    {
        $user = User::factory()
                    ->has(Comment::factory()->count(4))
                    ->create();

        $this->assertEquals(2, $user->achievements()->count());
        $this->assertEquals("3 Comments Written", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }

    /**
     * Test three achievement unlocked from 5 comments
     */
    public function test_three_achievements_unlocked_by_5_comments(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(5))
            ->create();

        $this->assertEquals(3, $user->achievements()->count());
        $this->assertEquals("5 Comments Written", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }

    /**
     * Test four achievement unlocked from 10 comments
     */
    public function test_four_achievements_unlocked_by_10_comments(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(13))
            ->create();

        $this->assertEquals(4, $user->achievements()->count());
        $this->assertEquals("10 Comments Written", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }

    /**
     * Test five achievement unlocked from 20 comments
     */
    public function test_five_achievements_unlocked_by_20_comments(): void
    {
        $user = User::factory()
            ->has(Comment::factory()->count(24))
            ->create();

        $this->assertEquals(5, $user->achievements()->count());
        $this->assertEquals("20 Comments Written", $user->achievements()->orderBy('id', 'desc')->first()->title);

    }


}
