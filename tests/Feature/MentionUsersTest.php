<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JaneDoe']);
        $this->signIn($john);

        $jane =create('App\User', ['name' => 'jane']);

        $thread = create('App\Thread');
        $reply = make('App\Reply', [
            'body' => '@jane look at this'
            ]);
        $this->post($thread->path().'/replies', $reply->toArray());
        $this->assertCount(1, $jane->fresh()->notifications);
    }
    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_charactiers()
    {
        create('App\User', ['name' => 'JaneDoe']);
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JohnDoe2']);

        $result = $this->json('GET', 'api/users', ['name' => 'john']);
        $this->assertCount(2, $result->json());
    }
}
