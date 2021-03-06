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

        // Then their reply saved in database
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
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

    /** @test */
    public function unauthenticated_users_can_not_delete_replies()
    {
        $reply = create('App\Reply');
        $this->delete("replies/{$reply->id}")
        ->assertRedirect('login');
        $this->signIn()
        ->delete("replies/{$reply->id}")
        ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply', ['user_id' =>auth()->id()]);
        $this->delete("replies/{$reply->id}")
        ->assertStatus(302);
        $this->assertDatabaseMissing('replies', ['id' =>$reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }
    /** @test */
    public function authorized_users_can_edit_replies()
    {
        $this->signIn();
        $updatedReply = "you have been changed";
        $reply = create('App\Reply', ['user_id' =>auth()->id()]);
        $this->patch("replies/{$reply->id}", ['body' => $updatedReply ]);
        //->assertStatus(302);
        $this->assertDatabaseHas('replies', ['id' =>$reply->id, 'body' => $updatedReply ]);
    }
    /** @test */
    public function unauthorized_users_can_not_edit_replies()
    {
        // $this->signIn();
        $reply = create('App\Reply');
        $this->patch("replies/{$reply->id}")
          ->assertRedirect('login');
        $this->signIn()
          ->patch("replies/{$reply->id}")
          ->assertStatus(403);
    }
    // /** @test */
    // public function replies_that_contain_spam_may_not_be_created()
    // {
    //     $this->signIn();
        
    //     //add a thread
    //     $thread = create('App\Thread');

    //     //When a user adds a reply to thread
    //     $reply = make('App\Reply', [
    //         'body' => 'yahoo customer support'
    //     ]);
       
    //     $this->post($thread->path().'/replies', $reply->toArray())
    //     ->assertStatus(422);
    // }
    /** @test */
    public function users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Standard Reply'
        ]);
     
        $this->post($thread->path().'/replies', $reply->toArray())->assertStatus(201);
      
       
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(403);
    }
}
