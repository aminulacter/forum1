<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\ThreadReceivedNewReply;

class Thread extends Model
{
    use RecordsActivity;
    protected $guarded = [];
    protected $with=['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replies_Count', function ($builder) {
        //     $builder->withCount('replies');
        // });
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));
   
        return $reply;
    }

   
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
    
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId?:auth()->id())->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                ->where('user_id', auth()->id())
                ->exists();
    }

    public function hasUpdatesFor($user)
    {
        // $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
        
        $key = $user->VisitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }
}
