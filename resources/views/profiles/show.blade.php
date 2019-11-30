@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="page-header">
        <h1>{{ $profileUser->name }}
       
    </div>
    {{-- @foreach ($threads as $thread)
        <div class="card mt-3">
            <div class="card-header">
                <div class="level">
                     <span class="flex">
                     <a href="{{ route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:   
                     <a href="{{ $thread->path() }}"> {{ $thread->title }}</a>
                </span>
            <span>{{ $thread->created_at->diffForHumans() }}</span>
                </div>
               
            </div>

            <div class="card-body">  
                {{ $thread->body }}
            </div>
        </div> 
        @endforeach
        {{ $threads->links()}} --}}
         @forelse ($activities as $date=>$activity)
         <h3 class="page-header">{{$date}}</h3>
            @foreach($activity as $record)
                @if(view()->exists("profiles.activities.{$record->type}"))
                  @include("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
            @endforeach
            @empty
                <p>There is no activity for this user yet</p>
        @endforelse
        


        </div>
    </div>
    
</div>


@endsection