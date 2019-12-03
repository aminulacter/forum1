<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Notifications\DatabaseNotification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        //create(DatabaseNotification::class);
        $thread = create('App\Thread')->subscribe();
        
        $this->assertCount(0, auth()->user()->notifications);
        
        $thread->addReply([
            'user_id' =>auth()->id(),
            'body' => 'Some reply here'
        ]);


        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' =>create('App\User')->id,
            'body' => 'Some reply here'
        ]);
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);
       
        $this->assertCount(1, $this->get("/profiles/" . auth()->user() . "/notifications")->json());
    }



    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);
        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->fresh()->notifications);
            $this->delete("/profiles/{$user->name}/notifications/". $user->unreadNotifications->first()->id);
            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
