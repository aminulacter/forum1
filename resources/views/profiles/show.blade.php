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
         @foreach ($activities as $date=>$activity)
         <h3 class="page-header">{{$date}}</h3>
            @foreach($activity as $record)
                  @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endforeach
        @endforeach
        


        </div>
    </div>
    
</div>


@endsection