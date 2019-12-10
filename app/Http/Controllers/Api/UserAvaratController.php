<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class UserAvaratController extends Controller
{
    
    public function store(User $user)
    {
       
        request()->validate([
            'avatar' => 'required|image'
        ]);
       $avatar = request()->file('avatar')->store('avatars');
       $user->update([
            'avatar_path' => request()->file('avatar')->store('avatars')
            
        ]);
        
         return response(['message' => 'File Successfully uploaded']);
    }
}
