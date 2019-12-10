<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path', 'confirmation_token', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

   

    public function getRouteKeyName()
    {
        return 'name';
    }
    
    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }
    public function activity()
    {
        return $this->hasMany('App\Activity');
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed' => 'boolean'
    ];

    public function VisitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }
    
    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ?:'avatars/default.jpg');
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();
    }

    public function isAdmin()
    {
        return in_array($this->name, ['aminul', 'acter']);
    }
}
