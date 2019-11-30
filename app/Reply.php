<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
   
    protected $guarded = [];
    protected $with =['owner','favorites'];
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }
    public function path()
    {
        return $this->thread->path()."#reply-{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    protected $appends = ['favorites_count', 'isFavorited' ];

   

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
