<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class UserAvaratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store()
    {
        $this->validate(request(), [
            'avatar' => ['required', 'image']
        ]);
        dd(request()->file('avatar'));
        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);
        
        
           
        
        
        
        // request()->validate([
        //   'avatar' => 'required|image'
        // ]);
       
        // auth()->user()->update([
        //     'avatar_path' => request()->file('avatar')->storeAs('avatars', 'public')
        //      ]);
      
        return back();
    }
}
