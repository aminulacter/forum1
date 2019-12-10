<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function only_members_can_add_avatars()
    {
        $user = create('App\User');
        $this->json('POST', 'api/users/{$user->name}/avatar')
       ->assertStatus(401);
    }
    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $user = create('App\User', ['name' => 'JaneDoe']);
        $this->signIn($user);
       // dd($user);
        $this->json('POST', 'api/users/'. $user->name. '/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }

    /** @test */
    // public function a_user_may_add_an_avatar_to_their_profile()
    // {
    //     $user = create('App\User');
    //     $this->signIn($user);
    //     Storage::fake('public');
    //     $this->json('POST', 'api/users/'. $user->name. '/avatar', [
    //         'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
    //     ]);
    //     $this->assertEquals(asset('avatars/'.$file->hashName()), $user->avatar());
    //    // Storage::disk('public')->assertE('avatars/' . $file->hashName());
    // }
}
