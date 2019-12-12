<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        $this->signIn();
    }
    
    /** @test */
    public function unauthorized_users_cannot_update_theads()
    {
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);
        $this->patch($thread->path(), [])->assertStatus(403);
    }

    /** @test */
    public function a_thread_requires_a_body_and_a_title_to_be_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->patch($thread->path(), [
            'title' => 'Changed',

        ])->assertSessionHasErrors('body');
        $this->patch($thread->path(), [
            'body' => 'Changed',

        ])->assertSessionHasErrors('title');
    }





    /** @test */
    public function a_thread_can_be_updated_by_its_creators()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed body', $thread->body);
        });
    }
}
