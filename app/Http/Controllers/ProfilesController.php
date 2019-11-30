<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Activity;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
       // return Activity::feed($user);
       //dd($activities);
        return view(
            'profiles.show',
            [
            'profileUser' => $user,
            'activities' => Activity::feed($user),
            
             ]
        );
    }
}
