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
}
