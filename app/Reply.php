<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
   
    protected $guarded = [];
    protected $appends = ['favorites_count', 'isFavorited', 'isBest'];
    protected $with =['owner','favorites'];
    protected static function boot()
    {
        parent::boot();
        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });
    }
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }
  

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
   

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);
        return $matches[1];
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return  $this->isBest();
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    // public function favorites()
    // {
    //     return $this->morphMany('App\Favorite', 'favorited');
    // }

    // public function favorite()
    // {
    //     $attributes = ['user_id' => auth()->id()];
    //     if (! $this->favorites()->where($attributes)->exists()) {
    //         return  $this->favorites()->create($attributes);
    //     }
    // }

    // public function isFavorited()
    // {
    //     return !! $this->favorites()->where('user_id', auth()->id())->count();
    // }

    // public function getFavoritesCountAttribute()
    // {
    //     return $this->favorites->count();
    // }
    // public function getIsFavoritedAttribute(){
    //     return !! $this->isFavorited();
    // }
}
