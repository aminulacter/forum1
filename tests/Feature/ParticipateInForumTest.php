<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        // $this->withoutExceptionHandling()
        //     ->expectException('Illuminate\Auth\AuthenticationException');
       
        $this->post('threads/some-channel/1/replies', [])
        ->assertRedirect('/login');
    }


    /** @test */
    public function an_authentic_user_may_participate_in_forum_threads()
    {
        //given we have a authenticated user
      
        $this->signIn();
        
        //add a thread
        $thread = create('App\Thread');

        //When a user adds a reply to thread
        $reply = make('App\Reply');
        $this->post($thread->path().'/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())
        ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn();
        
        //add a thread
        $thread = create('App\Thread');

        //When a user adds a reply to thread
        $reply = make('App\Reply', ['body' => null]);
        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertSessionHasErrors('body');

    }
}
