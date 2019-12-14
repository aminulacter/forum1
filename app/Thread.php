<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Str;

use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity, Searchable;
    protected $guarded = [];
    protected $with=['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    protected $appends = ['isSubscribedTo'];

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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
        if ($this->locked) {
            throw new \Exception('Thread is locked');
        }

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
    protected $casts = [
        'locked' => 'boolean'
    ];
    public function hasUpdatesFor($user)
    {
        // $key = sprintf("users.%s.visits.%s", auth()->id(), $this->id);
        
        $key = $user->VisitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);
     
        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }
   

        $this->attributes['slug'] = $slug;
    }
    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }
}
