<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Carbon\Carbon;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_an_owner()
    {
        $reply = create('App\Reply');
        $this->assertInstanceOf('App\User', $reply->owner);
    }
    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');
        $this->assertTrue($reply->wasJustPublished());
       
        $reply->created_at = Carbon::now()->subWeek();
        // dd($reply->created_at);
        $this->assertFalse($reply->wasJustPublished());
    }
}
