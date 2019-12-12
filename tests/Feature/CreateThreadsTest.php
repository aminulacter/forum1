<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Activity;
use App\Rules\Recaptcha;
use App\Thread;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setup():void
    {
        parent::setUp();
       
        app()->singleton(Recaptcha::class, function(){
            return \Mockery::mock(Recaptcha::class, function($m){
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function guest_may_not_create_threads()
    {
        $this->get('/threads/create')
        ->assertRedirect(route('login'));
      
        $this->post('/threads')
        ->assertRedirect(route('login'));
    }
   

  /** @test */
  public function new_users_must_first_confirm_their_email_address_before_creating_threads()
  {
    $user = factory('App\User')->states('unconfirmed')->create();
    $this->signIn($user);
     $thread = make('App\Thread');
     $this->post('/threads', $thread->toArray())
     ->assertRedirect('/threads')
      ->assertSessionHas('flash', 'You must first confirm your email address.');
  }
   
   
    /** @test */
    public function an_user_can_create_new_forum_threads()
    {
        // $this->signIn();

        // $thread = make('App\Thread');

        // $response = $this->post('/threads', $thread->toArray());
        $response = $this->publishThread(['title' =>'some Title', 'body' => 'some Body']);

        $this->get($response->headers->get('Location'))
            ->assertSee('some Title')
            ->assertSee('some Body');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
         ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
         ->assertSessionHasErrors('body');
    }

      /** @test */
      public function a_thread_requires_recaptcha_verification()
      {
        unset(app()[Recaptcha::class]);  
        $this->publishThread(['g-recaptcha-response' => 'test'])
           ->assertSessionHasErrors('g-recaptcha-response');
      }
  

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id' => null])
         ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 999])
         ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_theads()
    {
        $thread = create('App\Thread');
        $this->delete($thread->path())
        ->assertRedirect(route('login'));
        $this->signIn();
        //$this->delete($thread->path())->assertRedirect(route('login'));
        $this->delete($thread->path())->assertStatus(403);
    }
   
    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

   

   
    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make('App\Thread', $overrides);
        return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        
        $response = $this->getJson($thread->path(). '/replies')->json();
     
        $this->assertCount(1, $response['data']);
        // $this->assertEquals(2, 
        $response(['total']);
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();
      
        $thread =create('App\Thread', ['title' => 'Foo Title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
       

    }

    /** @test */
    public function a_thread_with_a_title_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();
        $thread =create('App\Thread', ['title' => 'Some Title 24']);
        $thread = $this->postJson('/threads', $thread->toArray()+ ['g-recaptcha-response' => 'token'])->json();
        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
    }
}
