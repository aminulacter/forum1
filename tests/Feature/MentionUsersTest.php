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
        $john = create('App\User',['name' => 'JaneDoe']);
        $this->signIn($john);

        $jane =create('App\User', ['name' => 'jane']);

        $thread = crate('App\Tread');
        $reply = make('App\Reply', [
            'body' => '@janeDoe look at this'
            ]);
        $this->post($thread->path().'/replies', $reply->toArray());
    }
}
