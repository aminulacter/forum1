<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);
        $this->assertEquals($reply->id, $user->lastReply->id);
    }
    /** @test */
    public function a_user_can_determine_their_avatarpath()
    {
        $user = create('App\User');
        $this->assertEquals('http://localhost/avatars/default.jpg', $user->avatar());
        $user->avatar_path = 'avatars/me.jpg';
        $this->assertEquals('http://localhost/avatars/me.jpg', $user->avatar());
    }
}
