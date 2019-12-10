<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp():void
    {
        parent::setup();
        $this->trending = new Trending();
        $this->trending->reset();
    }
    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = create('App\Thread');
        $this->call('GET', $thread->path());
        $trending = $this->trending->get();
       // dd($trending);
        $this->assertCount(1,  $this->trending->get());
        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
