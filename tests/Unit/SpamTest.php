<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalida_keywords()
    {
        $spam = new Spam();
        $this->assertTrue($spam->detect('Innocent reply here'));
        $this->expectException('Exception');
        $spam->detect('yahoo customer support');
    }

    /** @test */
    public function it_checks_for_any_key_being_heldd_down()
    {
        $spam = new Spam();
        $this->expectException('Exception');
        $spam->detect('Hello world aaaaaaaaaaa');
    }
}
