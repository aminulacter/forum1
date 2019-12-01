<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->replies()->create($reply);
        $this->subscriptions
        ->filter(function ($sub) use ($reply){
            return $sub->user_id != $reply->user_id;
        })->each->notify($reply);
        // ->each(function ($sub) use($reply){
        //     $sub->user->notify(new ThreadWasUpdated($this, $reply));
        // });
       

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
}
